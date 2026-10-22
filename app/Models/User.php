<?php

namespace App\Models;

use App\Enums\AdminRole;
use App\Enums\MemberType;
use App\Events\MemberEmailChanged;
use App\Events\MembershipEnded;
use App\Events\MembershipRestored;
use App\Events\MemberSignedUp;
use App\Events\MemberTypeChanged;
use App\Events\SignUpApproved;
use App\Events\SignUpRejected;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

#[Fillable([
    'email',
    'password',
    'first_name',
    'preposition',
    'last_name',
    'street',
    'house_number',
    'city',
    'zipcode',
    'country',
    'phone_number',
    'phone_number_alt',
    'emergency_number',
    'emergency_house_number',
    'emergency_street',
    'emergency_city',
    'emergency_zipcode',
    'emergency_country',
    'birthday',
    'member_type',
    'iban',
    'bic',
    'direct_debit',
    'remark',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'birthday' => 'date',
            'membership_ended_on' => 'date:Y-m-d',
            'pending_since' => 'date:Y-m-d',
            'direct_debit' => 'boolean',
            'member_type' => MemberType::class,
        ];
    }

    /**
     * Members who are neither former nor pending.
     */
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->whereNull('membership_ended_on')->whereNull('pending_since');
    }

    #[Scope]
    protected function former(Builder $query): void
    {
        $query->whereNotNull('membership_ended_on');
    }

    #[Scope]
    protected function pending(Builder $query): void
    {
        $query->whereNotNull('pending_since');
    }

    public static function signUp(array $data): self
    {
        $user = new self($data);

        // A date column, so the date in Amsterdam rather than in UTC.
        $user->pending_since = today(config('app.display_timezone'));
        $user->member_type = MemberType::Member;
        $user->membership_ended_on = null;

        $user->save();

        MemberSignedUp::dispatch($user);

        return $user;
    }

    /**
     * Updates the user from the member form's data.
     */
    public function updateFromForm(array $data): void
    {
        $previousType = $this->member_type;

        $this->update($data);

        // The lists move to the new email address before the member type's lists change.
        if ($this->wasChanged('email')) {
            MemberEmailChanged::dispatch($this, $this->getPrevious()['email']);
        }
        if ($this->member_type !== $previousType) {
            MemberTypeChanged::dispatch($this, $previousType, $this->member_type);
        }
    }

    /**
     * @return BelongsToMany<Role, $this>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @return BelongsToMany<Certificate, $this>
     */
    public function certificates(): BelongsToMany
    {
        return $this->belongsToMany(Certificate::class)
            ->withTimestamps()->withTrashed();
    }

    /**
     * @return HasMany<Zekering, $this>
     */
    public function zekeringen(): HasMany
    {
        return $this->hasMany(Zekering::class, 'created_by');
    }

    /**
     * @return HasMany<Registration, $this>
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function hasRole(AdminRole ...$roles): bool
    {
        foreach ($roles as $role) {
            if ($this->roles->contains($role->value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * The certificate abbreviations, as "abbr, abbr, ...".
     *
     * @return Attribute<string, never>
     */
    protected function certificateAbbreviations(): Attribute
    {
        return Attribute::get(fn (): string => $this->certificates->pluck('abbreviation')->implode(', '));
    }

    public function hasAnyRole(): bool
    {
        return $this->roles->isNotEmpty();
    }

    /**
     * The full name, as "first name preposition last name".
     *
     * @return Attribute<string, never>
     */
    protected function name(): Attribute
    {
        return Attribute::get(fn (): string => implode(' ', array_filter([$this->first_name, $this->preposition, $this->last_name])));
    }

    /**
     * The street and house number.
     */
    protected function address(): Attribute
    {
        return Attribute::get(fn (): string => "{$this->street} {$this->house_number}");
    }

    public function isFormerMember(): bool
    {
        return $this->membership_ended_on !== null;
    }

    public function isPendingMember(): bool
    {
        return $this->pending_since !== null;
    }

    public function isActiveMember(): bool
    {
        return $this->membership_ended_on === null && $this->pending_since === null;
    }

    public function endMembership(): void
    {
        $this->membership_ended_on = today(config('app.display_timezone'));
        $this->save();

        $this->roles()->detach();

        MembershipEnded::dispatch($this);
    }

    /**
     * Makes a former member active again; returns false when they're already active or have no email address.
     */
    public function restoreMembership(): bool
    {
        if ($this->isActiveMember()) {
            Log::notice('Member '.$this->name.' was attempted to made active, but is already active');
        }
        if (! $this->email) {
            Log::notice('Member '.$this->name." was attempted to made active, but doesn't have an email address");
        }
        if (! $this->email || $this->isActiveMember()) {
            return false;
        }

        DB::transaction(function () {
            $this->membership_ended_on = null;
            $this->save();
            MembershipRestored::dispatch($this);
        });

        return true;
    }

    public function approveSignUp(): void
    {
        DB::transaction(function () {
            $this->pending_since = null;
            $this->save();
            SignUpApproved::dispatch($this);
        });
    }

    /**
     * @throws RuntimeException when the user isn't a pending member
     */
    public function rejectSignUp(): void
    {
        if (! $this->isPendingMember()) {
            throw new RuntimeException('User is not a pending member.');
        }
        SignUpRejected::dispatch($this, $this->name);
        $this->delete();
    }
}

<?php

namespace App\Models;

use App\Enums\KindOfMember;
use App\Enums\Role;
use App\Events\MemberBecameOldMember;
use App\Events\MemberEmailChanged;
use App\Events\MemberKindChanged;
use App\Events\OldMemberBecameMember;
use App\Events\PendingUserApproved;
use App\Events\PendingUserCreated;
use App\Events\PendingUserRemoved;
use App\Models\ApplicationForm\ApplicationResponse;
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
    'firstname',
    'preposition',
    'lastname',
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
    'kind_of_member',
    'iban',
    'bic',
    'incasso',
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
            'lid_af' => 'date:Y-m-d',
            'pending_user' => 'date:Y-m-d',
            'incasso' => 'boolean',
            'kind_of_member' => KindOfMember::class,
        ];
    }

    /**
     * Members who are neither old nor pending.
     */
    #[Scope]
    protected function current(Builder $query): void
    {
        $query->whereNull('lid_af')->whereNull('pending_user');
    }

    #[Scope]
    protected function old(Builder $query): void
    {
        $query->whereNotNull('lid_af');
    }

    #[Scope]
    protected function pending(Builder $query): void
    {
        $query->whereNotNull('pending_user');
    }

    /**
     * Makes an unsaved user from the member form's data.
     */
    public static function fromForm(array $data): self
    {
        $user = new self($data);
        $user->incasso = array_key_exists('incasso', $data);

        return $user;
    }

    public static function createPending(array $data): self
    {
        $user = self::fromForm($data);

        // A date column, so the date in Amsterdam rather than in UTC.
        $user->pending_user = today(config('app.display_timezone'));
        $user->kind_of_member = KindOfMember::Member;
        $user->lid_af = null;

        $user->save();

        PendingUserCreated::dispatch($user);

        return $user;
    }

    /**
     * Updates the user from the member form's data.
     */
    public function updateFromForm(array $data): void
    {
        $data['incasso'] = array_key_exists('incasso', $data);

        $oldKindOfMember = $this->kind_of_member;

        $this->update($data);

        // The lists move to the new email address before the kind of member's lists change.
        if ($this->wasChanged('email')) {
            MemberEmailChanged::dispatch($this, $this->getPrevious()['email']);
        }
        if ($this->kind_of_member !== $oldKindOfMember) {
            MemberKindChanged::dispatch($this, $oldKindOfMember, $this->kind_of_member);
        }
    }

    /**
     * @return BelongsToMany<Rol, $this>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class);
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
     * @return HasMany<ApplicationResponse, $this>
     */
    public function applicationResponses(): HasMany
    {
        return $this->hasMany(ApplicationResponse::class);
    }

    protected function birthdayFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->birthday->format('d-m-Y'),
        );
    }

    public function hasRole(Role ...$roles): bool
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
     */
    public function getCertificationsAbbreviations(): string
    {
        return $this->certificates->pluck('abbreviation')->implode(', ');
    }

    public function hasBackendRights(): bool
    {
        return $this->roles->isNotEmpty();
    }

    /**
     * The name as "firstname preposition lastname".
     */
    public function getName(): string
    {
        $nameParts = [
            $this->firstname,
            $this->preposition,
            $this->lastname,
        ];

        return implode(' ', array_filter($nameParts));
    }

    public function getAddress(): string
    {
        return "{$this->street} {$this->house_number}";
    }

    public function isOldMember(): bool
    {
        return $this->lid_af !== null;
    }

    public function isPendingMember(): bool
    {
        return $this->pending_user !== null;
    }

    public function isActiveMember(): bool
    {
        return $this->lid_af === null && $this->pending_user === null;
    }

    public function removeAsActiveMember(): void
    {
        $this->lid_af = today(config('app.display_timezone'));
        $this->save();

        $this->roles()->detach();

        MemberBecameOldMember::dispatch($this);
    }

    /**
     * Makes an old member active again; returns false when they're already active or have no email address.
     */
    public function makeActiveMember(): bool
    {
        if ($this->isActiveMember()) {
            Log::notice('User '.$this->getName().' was attempted to made active, but is already active');
        }
        if (! $this->email) {
            Log::notice('User '.$this->getName()." was attempted to made active, but doesn't have an email address");
        }
        if (! $this->email || $this->isActiveMember()) {
            return false;
        }

        DB::transaction(function () {
            $this->lid_af = null;
            $this->save();
            OldMemberBecameMember::dispatch($this);
        });

        return true;
    }

    public function approveAsPendingMember(): void
    {
        DB::transaction(function () {
            $this->pending_user = null;
            $this->save();
            PendingUserApproved::dispatch($this);
        });
    }

    /**
     * @throws RuntimeException when the user isn't a pending member
     */
    public function removeAsPendingMember(): void
    {
        if (! $this->isPendingMember()) {
            throw new RuntimeException('User is not a pending member.');
        }
        PendingUserRemoved::dispatch($this, $this->getName());
        $this->delete();
    }
}

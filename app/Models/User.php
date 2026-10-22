<?php

namespace App\Models;

use App\Enums\AdminRole;
use App\Enums\KindOfMember;
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
            'kind_of_member' => KindOfMember::class,
        ];
    }

    /**
     * Members who are neither old nor pending.
     */
    #[Scope]
    protected function current(Builder $query): void
    {
        $query->whereNull('membership_ended_on')->whereNull('pending_since');
    }

    #[Scope]
    protected function old(Builder $query): void
    {
        $query->whereNotNull('membership_ended_on');
    }

    #[Scope]
    protected function pending(Builder $query): void
    {
        $query->whereNotNull('pending_since');
    }

    public static function createPending(array $data): self
    {
        $user = new self($data);

        // A date column, so the date in Amsterdam rather than in UTC.
        $user->pending_since = today(config('app.display_timezone'));
        $user->kind_of_member = KindOfMember::Member;
        $user->membership_ended_on = null;

        $user->save();

        PendingUserCreated::dispatch($user);

        return $user;
    }

    /**
     * Updates the user from the member form's data.
     */
    public function updateFromForm(array $data): void
    {
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
     * @return HasMany<ApplicationResponse, $this>
     */
    public function applicationResponses(): HasMany
    {
        return $this->hasMany(ApplicationResponse::class);
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

    public function removeAsActiveMember(): void
    {
        $this->membership_ended_on = today(config('app.display_timezone'));
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
            $this->membership_ended_on = null;
            $this->save();
            OldMemberBecameMember::dispatch($this);
        });

        return true;
    }

    public function approveAsPendingMember(): void
    {
        DB::transaction(function () {
            $this->pending_since = null;
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

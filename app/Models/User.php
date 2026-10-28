<?php

namespace App\Models;

use App\Enums\Role;
use App\Events\MemberBecameOldMember;
use App\Events\MemberKindChanged;
use App\Events\OldMemberBecameMember;
use App\Events\PendingUserApproved;
use App\Events\PendingUserCreated;
use App\Events\PendingUserRemoved;
use App\Models\ApplicationForm\ApplicationResponse;
use Carbon\Carbon;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
    use Notifiable, HasFactory, SoftDeletes;
    
    /**
     * All possible member types.
     * use trans('key') for nicely formatted string
     * 
     * @var array
     */
    public const KINDS_OF_MEMBER = [
        "member",
        "extraordinary_member",
        "reunist",
        "honorary_member",
        "member_of_merit",
        "trainer",
        "relationship",
    ];
    
    protected function casts(): array
    {
        return [
            'birthday' => 'date',
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
     *
     * @param array $data array of mass assignable attribute values
     * @throws \Exception if birthday parsing fails
     */
    public static function fromForm(array $data): self
    {
        $user = new self($data);
        $user->password = bcrypt($user->password);
        $birthday = new \DateTime($data['birthday']);
        $user->birthday = Carbon::createFromFormat('d-m-Y H:i', $birthday->format('d-m-Y') . ' ' . $birthday->format('H:i'));
        $user->incasso = array_key_exists("incasso", $data);

        return $user;
    }

    /**
     * Creates a new pending user.
     * Dispatches a PendingUserCreated event
     *
     * @param array $data array with the mass assignable user attributes
     * @throws \Exception if user birthday parsing fails
     */
    public static function createPending(array $data): self
    {
        $user = self::fromForm($data);

        //pending user specific
        $user->pending_user = Carbon::now();
        $user->kind_of_member = "member";
        $user->lid_af = null;

        $user->save();

        PendingUserCreated::dispatch($user);

        return $user;
    }

    /**
     * Updates the user from the member form's data.
     * Dispatches a MemberKindChanged event when the kind of member changes.
     *
     * @param array $data array of mass assignable attributes to be updated on the user.
     * @throws \Exception if the birthday parsing fails
     */
    public function updateFromForm(array $data): void
    {
        $birthday = new \DateTime($data['birthday']);
        $data['birthday'] = Carbon::createFromFormat('d-m-Y H:i', $birthday->format('d-m-Y') . ' ' . $birthday->format('H:i'));
        $data['incasso'] = array_key_exists("incasso", $data);
        $data['password'] = ($data['password'] != "") ? bcrypt($data['password']) : $this->password;

        $oldKindOfMember = $this->kind_of_member;

        $this->update($data); //update before firing the events so mailman can't desync if kind_of_member and email are updated in the same request

        //fire a MemberKindChanged event when the kind_of_member field is updated
        if ($data['kind_of_member'] != $oldKindOfMember) {
            MemberKindChanged::dispatch($this, $oldKindOfMember, $data['kind_of_member']);
        }
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class);
    }

    public function certificates(): BelongsToMany
    {
        return $this->belongsToMany(Certificate::class)
            ->withTimestamps();
    }
    public function applicationResponses(): HasMany
    {
        return $this->hasMany(ApplicationResponse::class);
    }
    
    /**
     * Format birthday
     *
     * @return Attribute formatted birthday model attribute
     */
    public function birthdayFormatted(): Attribute
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
     * Returns a string of the certificate abbreviations in the format "abbr, abbr, ..."
     * @return string
     */
    public function getCertificationsAbbreviations(): string
    {
        $abbreviations = [];
        foreach ($this->certificates as $certificate) {
            $abbreviations[] = $certificate->abbreviation;
        }
        return implode(', ', $abbreviations);
    }

    /**
     * Checks if a user has role like admin or content administrator
     * @return bool
     */
    public function hasBackendRights(): bool
    {
        return count($this->roles) > 0;
    }
    
    /**
     * Returns the name in the format "firstname preposition lastname".
     * @return string
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
    
    /**
     * Returns the address of the user in the format "street house number".
     * @return string
     */
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
        return ($this->lid_af === null && $this->pending_user === null);
    }
    
    /**
     * Makes a member an inactive/old member.
     * Dispatches a MemberBecameOldMember event.
     *
     * @return void
     */
    public function removeAsActiveMember(): void
    {
        $this->lid_af = Carbon::now();
        // Logs them out everywhere: their sessions and remember-me cookies stop working
        $this->setRememberToken(Str::random(60));
        $this->save();
        DB::table(config('session.table'))->where('user_id', $this->id)->delete();

        $this->roles()->detach();

        if (Auth::user()->id === $this->id) {
            Auth::logout();
        }
        
        MemberBecameOldMember::dispatch($this);
    }
    
    /**
     * Makes a member active.
     * Dispatches an OldMemberBecameMember event
     *
     * @return bool True if operation succeeded
     */
    public function makeActiveMember(): bool
    {
        if (!$this->email || $this->isActiveMember()) { //don't do anything if the member is already active or doesn't have an email address
            if ($this->isActiveMember()) Log::notice("User " . $this->getName() . " was attempted to made active, but is already active");
            if (!$this->email) Log::notice("User " . $this->getName() . " was attempted to made active, but doesn't have an email address");
            return false;
        }
        
        DB::transaction(function () {
            $this->lid_af = null;
            $this->save();
            OldMemberBecameMember::dispatch($this);
        });
        return true;
    }
    
    /**
     * Approves a pending member as a member.
     * Dispatches a PendingUserApproved event.
     * @return void
     */
    public function approveAsPendingMember(): void
    {
        DB::transaction(function () {
            $this->pending_user = null;
            $this->save();
            PendingUserApproved::dispatch($this);
        });
    }
    
    /**
     * Removes a pending member.
     * Dispatches a PendingUserRemovedEvent
     *
     * @return void
     * @throws RuntimeException when called on a non-pending member
     */
    public function removeAsPendingMember(): void
    {
        if (! $this->isPendingMember()) {
            throw new RuntimeException("User is not a pending member.");
        }
        PendingUserRemoved::dispatch($this, $this->getName());
        $this->delete();
    }

}

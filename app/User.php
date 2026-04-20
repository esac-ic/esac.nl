<?php

namespace App;

use App\Models\ApplicationForm\ApplicationResponse;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use Notifiable, HasFactory, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'firstname',
        'preposition',
        'lastname',
        'street',
        'houseNumber',
        'city',
        'zipcode',
        'country',
        'phonenumber',
        'phonenumber_alt',
        'emergencyNumber',
        'emergencyHouseNumber',
        'emergencystreet',
        'emergencycity',
        'emergencyzipcode',
        'emergencycountry',
        'birthDay',
        'kind_of_member',
        'IBAN',
        'BIC',
        'incasso',
        'remark',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
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
    
    protected $casts = [
        'birthDay' => 'date',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class);
    }

    public function certificates(): BelongsToMany
    {
        return $this->belongsToMany(Certificate::class)
            ->withTimestamps()->withTrashed();
    }
    
    /**
     * Format birthday
     * 
     * @return Attribute formatted birthday model attribute
     */
    public function birthDayFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->birthDay->format('d-m-Y'),
        );
    }

    public function hasRole(...$roles): bool
    {
        foreach ($roles as $rol) {
            if ($this->roles->contains($rol)) {
                return true;
            }
        }
        return false;
    }

    public function getCertificationsAbbreviations(): string
    {
        $abbreviations = [];
        foreach ($this->certificates as $certificate) {
            $abbreviations[] = $certificate->abbreviation;
        }
        return implode(', ', $abbreviations);
    }

    //checks if a user has role like admin or content administrator
    public function hasBackendRights(): bool
    {
        return count($this->roles) > 0;
    }

    public function getName(): string
    {
        $nameParts = [
            $this->firstname,
            $this->preposition,
            $this->lastname,
        ];

        return implode(' ', array_filter($nameParts));
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

    public function removeAsActiveMember(): void
    {
        $this->lid_af = Carbon::now();
        $this->save();

        $this->roles()->detach();

        if (Auth::user()->id === $this->id) {
            Auth::logout();
        }
    }

    public function makeActiveMember(): void
    {
        if ($this->email) {
            $this->lid_af = null;
            $this->save();
        }
    }

    public function getAddress(): string
    {
        return "{$this->street} {$this->houseNumber}";
    }

    public function applicationResponses(): HasMany
    {
        return $this->hasMany(ApplicationResponse::class);
    }

    public function approveAsPendingMember(): void
    {
        $this->pending_user = null;
        $this->save();
    }
    public function removeAsPendingMember(): void
    {
        $this->delete();
    }

}

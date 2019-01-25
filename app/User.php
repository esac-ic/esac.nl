<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

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
        'gender',
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

    public function roles(){
        return $this->belongsToMany('App\Rol','rol_user');
    }

    public function certificates(){
        return $this->belongsToMany('App\Certificate','certificate_user')
            ->withPivot('startDate')
            ->withTimestamps()->withTrashed();
    }

    public function photos(){
        return $this->hasMany(Photo::class);
    }

    public function albums(){
        return $this->hasMany(PhotoAlbum::class);
    }

    public function hasRole(...$rols){
        foreach ($rols as $rol){
            if($this->roles->contains($rol)){
                return true;
            }
        }
        return false;

    }

    public function getCertificationsAbbreviations(){
        $certificates = "";
        foreach ($this->certificates as $certificate){
            $certificates .= $certificate->abbreviation . ", ";
            info($certificate->abbreviation);

        }
        return trim($certificates,", ");
    }

    //checks if a user has role like admin or content administrator
    public function hasBackendRigths(){
        return count($this->roles) > 0;
    }

    public function getName(){
        $name = $this->firstname;
        $name .= ($this->preposition == null)? ' ' : ' ' . $this->preposition . ' ' ;
        $name .= $this->lastname;

        return $name;
    }

    public function isOldMember(){
        return $this->lid_af !== null;
    }

    public function isPendingMember(){
        return $this->pending_user !== null;
    }

    public function isActiveMember(){
        return ($this->lid_af === null && $this->pending_user === null); 
    }

    public function removeAsActiveMember(){
        $this->lid_af = Carbon::now();
        $this->save();
    }
    public function getAdress() {
        return $this->street . " " . $this->houseNumber;
    }

    public function applicationResponses(){
        return $this->hasMany(ApplicationResponse::class,'user_id');
    }

    public function approveAsPendingMember(){
        $this->pending_user = null;
        $this->save();
    }
    public function removeAsPendingMember(){
        $this->delete();
    }

}

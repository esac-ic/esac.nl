<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 14-2-2017
 * Time: 18:48
 */

namespace App\Repositories;

use App\Events\MemberKindChanged;
use App\Events\PendingUserCreated;
use App\User;
use Carbon\Carbon;

class UserRepository implements IRepository
{

    public function create(array $data): User
    {
        
        $user = $this->createUserFromDataArray($data);
        
        $user->save();

        return $user;
    }
    
    /**
     * Update a user.
     *
     * @param mixed $id id of the user to be updated
     * @param array $data array of mass assignable attributes to be updated on the user.
     * @return User|null the updated user if the id could be found
     * @throws \Exception if the birthday parsing fails
     */
    public function update(mixed $id, array $data): ?User
    {
        $user = $this->find($id);
        
        if ($user != null) {
            $birthDay = new \DateTime($data['birthDay']);
            $data['birthDay'] = Carbon::createFromFormat('d-m-Y H:i', $birthDay->format('d-m-Y') . ' ' . $birthDay->format('H:i'));
            $data['incasso'] = array_key_exists("incasso", $data);
            $data['password'] = ($data['password'] != "") ? bcrypt($data['password']) : $user->password;
            
            //fire a MemberKindChanged event when the kind_of_member field is updated
            if ($data['kind_of_member'] != $user->kind_of_member) {
                MemberKindChanged::dispatch($user, $user->kind_of_member, $data['kind_of_member']);
            }
            $user->update($data);
        }
        
        return $user;
    }

    public function delete($id): void
    {
        $user = $this->find($id);
        $user->certificates()->detach();
        $user->delete();
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return User::where($field, '=', $value)->get($columns);
    }

    public function all($columns = array('*'))
    {
        return User::all($columns);
    }

    public function getCurrentUsers($columns = array('*'), array $with = [])
    {
        return User::query()
            ->with($with)
            ->where([['lid_af', null], ['pending_user', null]])
            ->get($columns);
    }
    public function getCurrentUsersAfterSignupDate($columns = array('*'), $createAfter, array $with = [])
    {
        return User::query()
            ->with($with)
            ->where([['lid_af', null], ['pending_user', null], ['created_at', '>=', $createAfter]])
            ->get($columns);
    }

    public function getOldUsers($columns = array('*'))
    {
        return User::where('lid_af', '!=', null)->get($columns);
    }

    public function getPendingUsers($columns = array('*'))
    {
        return User::where('pending_user', '!=', null)->get($columns);
    }

    public function addRols($id, array $rols = array()): void
    {
        $user = $this->find($id);

        $user->roles()->sync($rols);
    }

    public function addCertificate($userid, $data): void
    {
        $user = $this->find($userid);

        $user->certificates()->attach($data['certificate_id']);
    }

    public function getUserCertificates($userid)
    {
        $user = $this->find($userid);
        return $user->certificates;
    }
    
    /**
     * Creates a new pending user.
     * Dispatches a PendingUserCreated event
     *
     * @param array $data array with the mass assignable user attributes
     * @return User the created pending user
     * @throws \Exception if user birthday parsing fails
     */
    public function createPendingUser(array $data): User
    {
        $user = $this->createUserFromDataArray($data);
        
        //pending user specific
        $user->pending_user = Carbon::now();
        $user->kind_of_member = "member";
        $user->lid_af = null;

        $user->save();
        
        PendingUserCreated::dispatch($user);

        return $user;
    }
    
    /**
     * Helper method to create a user object from a data array.
     *
     * @param array $data array of mass assignable attribute values
     * @return User
     * @throws \Exception if birthday parsing fails
     */
    private function createUserFromDataArray(array $data): User
    {
        $user = new User($data);
        $user->password = bcrypt($user->password);
        $birthDay = new \DateTime($data['birthDay']);
        $user->birthDay = Carbon::createFromFormat('d-m-Y H:i', $birthDay->format('d-m-Y') . ' ' . $birthDay->format('H:i'));
        $user->incasso = array_key_exists("incasso", $data);
        
        return $user;
    }
    
}

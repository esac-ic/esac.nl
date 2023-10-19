<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 14-2-2017
 * Time: 18:48
 */

namespace App\Repositories;

use App\User;
use Carbon\Carbon;

class UserRepository implements IRepository
{

    public function create(array $data)
    {

        $user = new User($data);
        $user->password = bcrypt($user->password);
        $birthDay = new \DateTime($data['birthDay']);
        $user->birthDay = Carbon::createFromFormat('d-m-Y H:i', $birthDay->format('d-m-Y') . ' ' . $birthDay->format('H:i'));
        $user->incasso = array_key_exists("incasso", $data);

        $user->save();

        return $user;
    }

    public function update($id, array $data)
    {
        $birthDay = new \DateTime($data['birthDay']);
        $user = $this->find($id);
        $data['birthDay'] = Carbon::createFromFormat('d-m-Y H:i', $birthDay->format('d-m-Y') . ' ' . $birthDay->format('H:i'));
        $data['incasso'] = array_key_exists("incasso", $data);
        $data['password'] = ($data['password'] != "") ? bcrypt($data['password']) : $user->password;

        $user->update($data);

        return $user;
    }

    public function delete($id)
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

    public function getOldUsers($columns = array('*'))
    {
        return User::where('lid_af', '!=', null)->get($columns);
    }

    public function getPendingUsers($columns = array('*'))
    {
        return User::where('pending_user', '!=', null)->get($columns);
    }

    public function addRols($id, array $rols = array())
    {
        $user = $this->find($id);

        $user->roles()->sync($rols);
    }

    public function addCertificate($userid, $data)
    {
        $user = $this->find($userid);

        $user->certificates()->attach($data['certificate_id']);
    }

    public function getUserCertificates($userid)
    {
        $user = $this->find($userid);
        return $user->certificates;
    }

    public function createPendingUser(array $data)
    {
        $user = new User($data);
        $user->password = bcrypt($user->password);
        $birthDay = new \DateTime($data['birthDay']);
        $user->birthDay = Carbon::createFromFormat('d-m-Y H:i', $birthDay->format('d-m-Y') . ' ' . $birthDay->format('H:i'));
        $user->incasso = array_key_exists("incasso", $data);

        //pending user specific
        $user->pending_user = Carbon::now();
        $user->kind_of_member = "member";
        $user->lid_af = null;

        $user->save();

        return $user;
    }

}

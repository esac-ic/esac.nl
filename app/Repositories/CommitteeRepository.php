<?php

namespace App\Repositories;

use App\Models\Committee;
use App\Repositories\IRepository;
use App\User;
use Illuminate\Support\Facades\Log;

class CommitteeRepository implements IRepository
{
    
    public function create(array $data)
    {
        $committee = Committee::create($data);
        $committee->save();
        return $committee;
    }
    
    public function update($id, array $data)
    {
        $committee = $this->find($id);
        $committee->update($data);
        return $committee;
    }
    
    public function delete($id): void
    {
        $this->find($id)->delete();
    }
    
    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }
    
    public function findBy($field, $value, $columns = array('*'))
    {
        return Committee::where($field, '=', $value)->get($columns);
    }
    
    public function all($columns = array('*'))
    {
        return Committee::all();
    }
    
    /**
     * Adds some users to a committee.
     *
     * @param $id int id of the committee
     * @param $userIds int | int[] the ids users to be added to the committee
     * @return void
     */
    public function addMembers(int $id, int|array $userIds): void
    {
        $committee = $this->find($id);
        $committee->members()->attach($userIds);
    }
    
    /**
     * Removes a user from the committee.
     *
     * @param int $id id of committee
     * @param int $userId id of user to be removed
     * @return void
     */
    public function removeMember(int $id, int $userId): void
    {
        $committee = $this->find($id);
        $committee->members()->detach($userId);
    }
}
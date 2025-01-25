<?php

namespace App\Repositories;

use App\Models\UserEventLogEntry;

class UserEventLogEntryRepository implements IRepository
{
    public function create(array $data)
    {
        $event = new UserEventLogEntry($data);
        $event->save();
        return $event;
    }

    public function update($id, array $data)
    {
        $event = $this->find($id);
        $event->eventType = $data['eventType'];
        $event->eventDetails = $data['eventDetails'];
        $event->user_id = $data['user_id'];
        $event->save();
        return $event;
    }

    public function delete($id)
    {
        $event = $this->find($id);
        $event->delete();
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns);
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return UserEventLogEntry::query()->where($field, '=', $value)->first($columns);
    }

    public function all($columns = array('*'))
    {
        return UserEventLogEntry::all($columns);
    }
}
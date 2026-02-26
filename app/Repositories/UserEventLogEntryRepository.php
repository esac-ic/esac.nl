<?php

namespace App\Repositories;

use App\Models\UserEventLogEntry;
use Illuminate\Database\Eloquent\Builder;

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
    
    /**
     * Find all user event logs that fulfill certain criteria
     * 
     * @param array|null $eventTypes the selected event types to be filtered on
     * @param \Carbon\Carbon $startDate start datetime of the selection
     * @param \Carbon\Carbon $endDate end datetime of the selection
     * 
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findLogs(Array | null $eventTypes, \Carbon\Carbon $startDate, \Carbon\Carbon $endDate)
    {
        $query = UserEventLogEntry::query()
        ->whereBetween('created_at', array($startDate, $endDate));
        
        if ($eventTypes != null)
        {
            $query = $query->whereIn('eventType', $eventTypes);
        }
        
        
        return $query->get()->makeHidden(['updated_at']);
    }
}
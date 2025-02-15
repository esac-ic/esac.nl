<?php

namespace App\Repositories;

use App\Models\UserEventLogEntry;
use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

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
     * Fina all user event logs that fulfill certain criterea
     * 
     * @param array|null $eventTypes the selected event types to be filtered on
     * @param array|null $names TODO the names of users to be filtered on
     * @param \Carbon\Carbon $startDate start datetime of the selection
     * @param \Carbon\Carbon $endDate end datetime of the selection
     * 
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findLogs(Array | null $eventTypes, Array | null $names, \Carbon\Carbon $startDate, \Carbon\Carbon $endDate)
    {
        // $query = DB::table('user_event_log_entries')
        // ->join('users', 'users.id', '=', 'user_event_log_entries.user_id')
        // ->select('users.get_name', 'user_event_log_entries.*')
        $query = UserEventLogEntry::query()
        // ->where(function (Builder $query) use ($startDate, $endDate) { //AND ((updated_at BETWEEN start and end) OR (created_at BETWEEN start and end))
        //     $query->whereBetween('created_at', array($startDate, $endDate));
        //     // ->orWhereBetween('updated_at', array($startDate, $endDate));
        // });
        ->whereBetween('created_at', array($startDate, $endDate));
                
        //todo add name filtering
        if ($names != null && false) 
        { 
            $query = $query->whereIn('user.getName', $names);
        }
        
        if ($eventTypes != null)
        {
            $query = $query->whereIn('eventType', $eventTypes);
        }
        
        
        return $query
        // ->dump()//debug
        ->get()
        ->makeHidden(['updated_at']);
    }
}
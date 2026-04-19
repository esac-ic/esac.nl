<?php

namespace App\Repositories;

use App\Models\UserEventLogEntry;
use BadMethodCallException;
use Illuminate\Database\Eloquent\Builder;

class UserEventLogEntryRepository implements IRepository
{
    /**
     * Create log entry.
     *
     * @param array $data array with the keys 'event_type', 'event_details' and 'user_id'
     */
    public function create(array $data): UserEventLogEntry
    {
        $validator = \Validator::make($data, [
            'event_type' => ['required', 'string'],
            'event_details' => ['required', 'string'],
            'user_id' => ['required'],
        ]);
        
        if ($validator->fails()) {
            throw new BadMethodCallException($validator->errors()->first());
        }
        
        $logEntry = new UserEventLogEntry();
        $logEntry->user()->associate($data['user_id']);
        $logEntry->event_type = $data['event_type'];
        $logEntry->event_details = $data['event_details'];
        $logEntry->save();
        return $logEntry;
    }
    
    /**
     * Not implemented, logs should not be able to change after creation
     */
    public function update($id, array $data)
    {
        throw new BadMethodCallException('Not implemented');
    }
    
    /**
     * NOT implemented, we don't want to delete logs, so this method is not available.
     * @deprecated
     */
    public function delete($id)
    {
        throw new BadMethodCallException('Not implemented');
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
            $query = $query->whereIn('event_type', $eventTypes);
        }
        
        
        return $query->get()->makeHidden(['updated_at']);
    }
}
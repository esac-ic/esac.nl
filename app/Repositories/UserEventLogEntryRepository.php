<?php

namespace App\Repositories;

use App\Models\UserEventLogEntry;
use BadMethodCallException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class UserEventLogEntryRepository implements IRepository
{
    /**
     * Create log entry.
     *
     * @param array $data array with the keys 'event_type', 'event_details' and 'user_id'
     */
    public function create(array $data): UserEventLogEntry
    {
        $validated = \Validator::make($data, [
            'event_type' => ['required', 'string'],
            'event_details' => ['required', 'string'],
            'user_id' => ['nullable', 'integer'],
        ])->validate();
        
        $logEntry = new UserEventLogEntry();
        
        if ($validated['user_id'])
            $logEntry->user()->associate($validated['user_id']);
        $logEntry->event_type = $validated['event_type'];
        $logEntry->event_details = $validated['event_details'];
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

    public function find($id, $columns = ['*'])
    {
        return $this->findBy('id', $id, $columns);
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        return UserEventLogEntry::query()->where($field, '=', $value)->first($columns);
    }

    public function all($columns = ['*'])
    {
        return UserEventLogEntry::all($columns);
    }
    
    /**
     * Find all user event logs that fulfill certain criteria
     *
     * @param array|null $eventTypes the selected event types to be filtered on
     * @param Carbon $startDate start datetime of the selection
     * @param Carbon $endDate end datetime of the selection
     *
     * @return Builder[]|Collection
     */
    public function findLogs(Array | null $eventTypes, Carbon $startDate, Carbon $endDate)
    {
        $query = UserEventLogEntry::query()
        ->whereBetween('created_at', [$startDate, $endDate]);
        
        if ($eventTypes != null)
        {
            $query = $query->whereIn('event_type', $eventTypes);
        }
        
        
        return $query->get()->makeHidden(['updated_at']);
    }
}
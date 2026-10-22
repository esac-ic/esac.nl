<?php

namespace App\Http\Controllers;

use App\Exports\UserEventLogExport;
use App\Models\UserEventLogEntry;
use App\Repositories\UserEventLogEntryRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Carbon;

class UserEventLogEntryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:view-user-event-log'),
        ];
    }

    public function __construct(private readonly UserEventLogEntryRepository $repository) 
    {
    }
    
    public function index(): View 
    {
        $events = $this->repository->all(['*']);
        $eventTypes = UserEventLogEntry::distinct('event_type')->pluck('event_type')->sort();
        return view('beheer.userEventLog.index', compact('events', 'eventTypes'));
    }
    
    public function export(Request $request, UserEventLogEntryRepository $repo)
    {
        $eventTypes = $request->eventTypes;
        $startDate = Carbon::parse($request->startDate);
        $endDate = Carbon::parse($request->endDate);

        $export = new UserEventLogExport($repo, $eventTypes, $startDate, $endDate);
        return $export->download($export->fileName() . '.xlsx');
    }
}

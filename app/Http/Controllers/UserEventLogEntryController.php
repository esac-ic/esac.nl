<?php

namespace App\Http\Controllers;

use App\Exports\UserEventLogExport;
use App\Models\UserEventLogEntry;
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

    public function index(): View 
    {
        $events = UserEventLogEntry::all();
        $eventTypes = UserEventLogEntry::distinct('event_type')->pluck('event_type')->sort();
        return view('beheer.userEventLog.index', compact('events', 'eventTypes'));
    }
    
    public function export(Request $request)
    {
        $eventTypes = $request->eventTypes;
        $startDate = Carbon::parse($request->startDate);
        $endDate = Carbon::parse($request->endDate);

        $export = new UserEventLogExport($eventTypes, $startDate, $endDate);
        return $export->download($export->fileName() . '.xlsx');
    }
}

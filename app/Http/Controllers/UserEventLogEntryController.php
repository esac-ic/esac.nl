<?php

namespace App\Http\Controllers;

use App\Exports\UserEventLogExport;
use App\Models\UserEventLogEntry;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        return view('beheer.userEventLog.index', [
            'events' => UserEventLogEntry::all(),
            'eventTypes' => UserEventLogEntry::distinct('event_type')->pluck('event_type')->sort(),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $request->validate([
            'eventTypes' => ['nullable', 'array'],
            'eventTypes.*' => ['string'],
            'startDate' => ['required', 'date_format:d-m-Y H:i'],
            'endDate' => ['required', 'date_format:d-m-Y H:i'],
        ]);

        $export = new UserEventLogExport(
            $request->input('eventTypes'),
            $request->date('startDate', 'd-m-Y H:i', config('app.display_timezone'))->utc(),
            $request->date('endDate', 'd-m-Y H:i', config('app.display_timezone'))->utc(),
        );

        return $export->download($export->fileName().'.xlsx');
    }
}

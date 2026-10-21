<?php

namespace App\Http\Controllers;

use App\Exports\UserEventLogExport;
use App\Repositories\UserEventLogEntryRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class UserEventLogEntryController extends Controller
{
    public function __construct(private readonly UserEventLogEntryRepository $repository) 
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Administrator'));
    }
    
    public function index(): View 
    {
        $events = $this->repository->all(['*']);
        return view('beheer.userEventLog.index', compact('events'));
    }
    
    public function export(Request $request, UserEventLogEntryRepository $repo)
    {
        $eventTypes = $request->eventTypes;
        
        $names = $request->names;
        $startDate = Carbon::parse($request->startDate);
        $endDate = Carbon::parse($request->endDate);
        
        $export = new UserEventLogExport($repo, $eventTypes, $names, $startDate, $endDate);
        return $export->download($export->fileName() . '.xlsx');
    }
}

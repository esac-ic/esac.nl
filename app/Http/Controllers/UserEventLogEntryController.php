<?php

namespace App\Http\Controllers;

use App\Models\UserEventLogEntry;
use App\Repositories\UserEventLogEntryRepository;
use App\Exports\UserEventLogExport;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class UserEventLogEntryController extends Controller
{
    private UserEventLogEntryRepository $repository;
    
    public function __construct(UserEventLogEntryRepository $repository) 
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Administrator'));
        
        $this->repository = $repository;
    }
    
    public function index() 
    {
        $events = $this->repository->all(['*']);
        // $events = $this->repository->all(array('eventType', 'eventDetails', 'user', 'created_at'));
        return view('beheer.userEventLog.index', compact('events'));
    }
    
    public function destroy(Request $request, UserEventLogEntry $entry)
    {
        $this->repository->delete($entry->id);

        Session::flash("message", 'Entry removed');
        return redirect('/userEventLog');
    }
    
    // public function exportDialog(Request $request)
    // {
    //     return view('beheer.userEventLog.export_dialog');
    // }
    
    public function export(Request $request, Excel $excel, UserEventLogEntryRepository $repo)
    {
        Log::debug($request);
        
        
        $eventTypes = $request->eventTypes;
        if ($eventTypes == null) //if no event type was selected, default is to select all
        {
            $eventTypes = \App\Enums\UserEventTypes::values();
            // Log::debug($eventTypes);
        }
        
        $names = $request->names;
        $startDate = Carbon::parse($request->startDate);
        $endDate = Carbon::parse($request->endDate);
        
        $export = new UserEventLogExport($repo, $eventTypes, $names, $startDate, $endDate);
        \Log::debug($export->fileName());
        return $excel->download($export, $export->fileName() . '.xlsx');
        
        // $events = $this->repository->findLogs($eventTypes, $names, $startDate, $endDate);
        // $events->downloadExcel('userEventLog.xlsx', Excel::XLSX, true);
        // Session::flash("message", "Downloaded event log");
        // return redirect('/userEventLog');
    }
    
    public function validateInput(Request $request)
    {
        $this->validate($request, [
            "eventTypes" => "array:"
        ]);
    }
}

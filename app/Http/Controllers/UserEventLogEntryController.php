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
use PhpOffice\PhpSpreadsheet\Exception;

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
        return view('beheer.userEventLog.index', compact('events'));
    }
    
    public function export(Request $request, Excel $excel, UserEventLogEntryRepository $repo)
    {
        $eventTypes = $request->eventTypes;
        
        $names = $request->names;
        $startDate = Carbon::parse($request->startDate);
        $endDate = Carbon::parse($request->endDate);
        
        $export = new UserEventLogExport($repo, $eventTypes, $names, $startDate, $endDate);
        return $excel->download($export, $export->fileName() . '.xlsx');
    }
}

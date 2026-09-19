<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    public function index()
    {
        return Committee::all();
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'abbreviation' => ['required'],
            'description' => ['required'],
        ]);
        
        return Committee::create($data);
    }
    
    public function show(Committee $committee)
    {
        return view('beheer.committee.show', compact('committee'));
    }
    
    public function update(Request $request, Committee $committee)
    {
        $data = $request->validate([
            'name' => ['required'],
            'abbreviation' => ['required'],
            'description' => ['required'],
        ]);
        
        $committee->update($data);
        
        return $committee;
    }
    
    public function destroy(Committee $committee)
    {
        $committee->delete();
        
        return response()->json();
    }
    
    public function addMembers(Request $request, Committee $committee)
    {
        \Log::info($request);
        //TODO in progress
        $data = $request->validate([
            'member_ids' => ['required', 'array'],
        ]);
        \Log::info(print_r($data, true));
        \Log::info("i");
        $committee->members()->attach($data['member_ids']);
        
        return redirect()->route('committees.show', $committee);
    }
}

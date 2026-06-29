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
        return $committee;
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
    
    public function addMember(Request $request, Committee $committee)
    {
        //TODO in progress
        $data = $request->validate([
            'committee_id' => ['required'],
            'member_id' => ['required'],
        ]);
        $committee->members()->attach($data['member_id']);
        
        return redirect()->route('committees.show', $committee);
    }
}

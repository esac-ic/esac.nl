<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Models\User;
use Illuminate\Http\Request;
use InvalidArgumentException;

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
        $data = $request->validate([
            'member_ids' => ['required', 'array'],
        ]);
        
        //make sure we don't add a member who is already in the committee
        $newMemberIds = collect($data['member_ids'])->diff($committee->members()->allRelatedIds());
        
        $committee->members()->attach($newMemberIds);
        
        return redirect()->route('committees.show', $committee);
    }
    
    
    public function removeMember(Committee $committee, User $member)
    {
        if ($committee->members()->find($member))
        {
            $committee->members()->detach($member->id);
        }
        else
        {
            throw new InvalidArgumentException("'$member->firstname' cannot be removed from '$committee->name' because they are not in the committee.");
        }
        return redirect()->route('committees.show', $committee);
    }
}

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
        $committees = Committee::all();
        return view('beheer.committee.index', compact('committees'));
    }
    
    public function create()
    {
        return view('beheer.committee.create');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'abbreviation' => ['required'],
        ]);
        
        $committee = Committee::create($data);
        return to_route('beheer.committees.show', $committee);
    }
    
    public function show(Committee $committee)
    {
        return view('beheer.committee.show', compact('committee'));
    }
    
    public function edit(Committee $committee)
    {
        return view('beheer.committee.edit', compact('committee'));
    }
    
    public function update(Request $request, Committee $committee)
    {
        $data = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'abbreviation' => ['required'],
        ]);
        
        $committee->update($data);
        
        //TODO the behaviour in the other controllers is to redirect to the show view, but this seems somewhat unintuitive to me, what do you think is better?
        return to_route('beheer.committees.index');
//        return to_route('beheer.committees.show', $committee);
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
        
        return redirect()->route('beheer.committees.show', $committee);
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
        return redirect()->route('beheer.committees.show', $committee);
    }
    
    public function setChairPersons(Request $request, Committee $committee)
    {
        $request->mergeIfMissing(['chair_persons' => []]); //if no checkboxes are checked, the key is missing from the request
        $validated = $request->validate([
            'chair_persons' => ['array'],
            'chair_persons.*' => ['integer', 'exists:users,id'],
        ]);
        
        $chairPersonIds = collect($validated['chair_persons'])->intersect($committee->members()->allRelatedIds());
        
        foreach ($committee->members()->allRelatedIds() as $memberId)
        {
            $committee->members()->updateExistingPivot($memberId, ['is_chair' => $chairPersonIds->contains($memberId)]);
        }
        
        return redirect()->route('beheer.committees.show', $committee);
    }
    
    public function exportMemberChanges()
    {
        //TODO
    }
}

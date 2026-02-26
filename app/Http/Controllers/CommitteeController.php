<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Repositories\CommitteeRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CommitteeController extends Controller
{
    private CommitteeRepository $repository;
    
    public function __construct(CommitteeRepository $repository)
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Administrator'));
        $this->repository = $repository;
        
    }
    public function index()
    {
        $committees = $this->repository->all();
        
        return view('beheer.committee.index', compact('committees'));
    }
    
    public function create()
    {
        $fields = [
            'title' => 'Add committee',
            'route' => route('beheer.committees.store'),
            'method' => 'POST',
        ];
        $committee = null;
        return view('beheer.committee.create_edit', compact('fields', 'committee'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'description' => ['nullable'],
            'email' => ['nullable', 'email', 'max:254'],
            'chair_id' => ['nullable', 'exists:users'],
            'abbreviation' => ['nullable'],
        ]);
        
        $this->repository->create($request->all());
        
        Session::flash('message', 'Committee create successfully!');
        
        return redirect()->route('beheer.committees.index');
    }
    
    public function show(Committee $committee)
    {
        return view('beheer.committee.show', compact('committee'));
    }
    
    public function edit(Committee $committee)
    {
        $fields = [
            'title' => 'Edit committee',
            'route' => route('beheer.committees.update', $committee->id),
            'method' =>  'PATCH',
        ];
        return view('beheer.committee.create_edit', compact('fields', 'committee'));
    }
    
    public function update(Request $request, Committee $committee)
    {
        $request->validate([
            'name' => ['required'],
            'description' => ['nullable'],
            'email' => ['nullable', 'email', 'max:254'],
            'chair_id' => ['nullable', 'exists:users'],
            'abbreviation' => ['nullable'],
        ]);
        
        $this->repository->update($committee->id, $request->all());
        
        Session::flash('message', 'Committee updated successfully!');
        return redirect()->route('beheer.committees.show', $committee->id);
    }
    
    public function destroy(Committee $committee)
    {
        $this->repository->delete($committee->id);
        return redirect()->route('beheer.committees.index');
    }
    
    public function removeMember(Request $request, Committee $committee, User $member)
    {
        $this->repository->removeMember($committee->id, $member->id);
    }
    
    public function addMembers(Request $request, Committee $committee)
    {
        $request->validate([
            'userIds' => ['required'],
        ]);
        $this->repository->addMembers($committee->id, $request->userIds);
    }
}

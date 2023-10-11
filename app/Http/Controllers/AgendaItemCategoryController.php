<?php

namespace App\Http\Controllers;

use App\AgendaItemCategory;
use App\Repositories\AgendaItemCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class AgendaItemCategoryController extends Controller
{
    private $agendaItemCategoryRepository;

    public function __construct(AgendaItemCategoryRepository $agendaItemCategoryRepository)
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Administrator'));

        $this->agendaItemCategoryRepository = $agendaItemCategoryRepository;
    }

    public function index()
    {
        $agendaItemCategories = $this->agendaItemCategoryRepository->all(['id', 'name']);
        return view('beheer.agendaItemCategory.index', compact('agendaItemCategories'));
    }

    public function create()
    {
        $fields = ['title' => 'Add an event category',
            'method' => 'POST',
            'url' => '/agendaItemCategories'];
        $agendaItemCategory = null;
        return view('beheer.agendaItemCategory.create_edit', compact(['fields', 'agendaItemCategory']));
    }

    public function store(Request $request)
    {
        $this->validateInput($request);
        $this->agendaItemCategoryRepository->create($request->all());

        Session::flash("message", 'Events category is added');
        return redirect('/agendaItemCategories');
    }

    public function show(Request $request, AgendaItemCategory $agendaItemCategory)
    {
        return view('beheer.agendaItemCategory.show', compact('agendaItemCategory'));
    }

    public function edit(Request $request, AgendaItemCategory $agendaItemCategory)
    {
        $fields = ['title' => 'Edit category',
            'method' => 'PATCH',
            'url' => '/agendaItemCategories/' . $agendaItemCategory->id];

        return view('beheer.agendaItemCategory.create_edit', compact('fields', 'agendaItemCategory'));
    }

    public function update(Request $request, AgendaItemCategory $agendaItemCategory)
    {
        $this->validateInput($request);
        $this->agendaItemCategoryRepository->update($agendaItemCategory->id, $request->all());

        Session::flash("message", 'Events category is edited');
        return redirect('/agendaItemCategories');
    }

    public function destroy(Request $request, AgendaItemCategory $agendaItemCategory)
    {
        $this->agendaItemCategoryRepository->delete($agendaItemCategory->id);

        Session::flash("message", 'Events category is removed');
        return redirect('/agendaItemCategories');
    }

    private function validateInput(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);
    }
}

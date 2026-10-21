<?php

namespace App\Http\Controllers;

use App\Models\AgendaItemCategory;
use App\Repositories\AgendaItemCategoryRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class AgendaItemCategoryController extends Controller
{
    public function __construct(private readonly AgendaItemCategoryRepository $agendaItemCategoryRepository)
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Administrator'));
    }

    public function index(): View
    {
        $agendaItemCategories = $this->agendaItemCategoryRepository->all(['id', 'name']);
        return view('beheer.agendaItemCategory.index', compact('agendaItemCategories'));
    }

    public function create(): View
    {
        $fields = ['title' => 'Add an event category',
            'method' => 'POST',
            'url' => '/agendaItemCategories'];
        $agendaItemCategory = null;
        return view('beheer.agendaItemCategory.create_edit', compact(['fields', 'agendaItemCategory']));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validateInput($request);
        $this->agendaItemCategoryRepository->create($request->all());

        Session::flash("message", 'Events category is added');
        return redirect('/agendaItemCategories');
    }

    public function show(Request $request, AgendaItemCategory $agendaItemCategory): View
    {
        return view('beheer.agendaItemCategory.show', compact('agendaItemCategory'));
    }

    public function edit(Request $request, AgendaItemCategory $agendaItemCategory): View
    {
        $fields = ['title' => 'Edit category',
            'method' => 'PATCH',
            'url' => '/agendaItemCategories/' . $agendaItemCategory->id];

        return view('beheer.agendaItemCategory.create_edit', compact('fields', 'agendaItemCategory'));
    }

    public function update(Request $request, AgendaItemCategory $agendaItemCategory): RedirectResponse
    {
        $this->validateInput($request);
        $this->agendaItemCategoryRepository->update($agendaItemCategory->id, $request->all());

        Session::flash("message", 'Events category is edited');
        return redirect('/agendaItemCategories');
    }

    public function destroy(Request $request, AgendaItemCategory $agendaItemCategory): RedirectResponse
    {
        $this->agendaItemCategoryRepository->delete($agendaItemCategory->id);

        Session::flash("message", 'Events category is removed');
        return redirect('/agendaItemCategories');
    }

    private function validateInput(Request $request): void
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);
    }
}

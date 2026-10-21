<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\AgendaItemCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Session;

class AgendaItemCategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('authorize:' . Role::Administrator->value),
        ];
    }

    public function index(): View
    {
        $agendaItemCategories = AgendaItemCategory::all(['id', 'name']);
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
        AgendaItemCategory::create($this->validateInput($request));

        Session::flash("message", 'Events category is added');
        return redirect('/agendaItemCategories');
    }

    public function show(AgendaItemCategory $agendaItemCategory): View
    {
        return view('beheer.agendaItemCategory.show', compact('agendaItemCategory'));
    }

    public function edit(AgendaItemCategory $agendaItemCategory): View
    {
        $fields = ['title' => 'Edit category',
            'method' => 'PATCH',
            'url' => '/agendaItemCategories/' . $agendaItemCategory->id];

        return view('beheer.agendaItemCategory.create_edit', compact('fields', 'agendaItemCategory'));
    }

    public function update(Request $request, AgendaItemCategory $agendaItemCategory): RedirectResponse
    {
        $agendaItemCategory->update($this->validateInput($request));

        Session::flash("message", 'Events category is edited');
        return redirect('/agendaItemCategories');
    }

    public function destroy(AgendaItemCategory $agendaItemCategory): RedirectResponse
    {
        $agendaItemCategory->delete();

        Session::flash("message", 'Events category is removed');
        return redirect('/agendaItemCategories');
    }

    private function validateInput(Request $request): array
    {
        return $request->validate([
            'name' => 'required|max:255',
        ]);
    }
}

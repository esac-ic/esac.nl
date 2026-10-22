<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaItemCategoryRequest;
use App\Models\AgendaItemCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AgendaItemCategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-agenda-categories'),
        ];
    }

    public function index(): View
    {
        return view('beheer.agendaItemCategory.index', [
            'agendaItemCategories' => AgendaItemCategory::all(['id', 'name']),
        ]);
    }

    public function create(): View
    {
        return view('beheer.agendaItemCategory.create_edit', [
            'agendaItemCategory' => null,
            'fields' => [
                'title' => 'Add an event category',
                'method' => 'POST',
                'url' => route('agendaItemCategories.store'),
            ],
        ]);
    }

    public function store(AgendaItemCategoryRequest $request): RedirectResponse
    {
        AgendaItemCategory::create($request->validated());

        return to_route('agendaItemCategories.index')->with('message', 'Events category is added');
    }

    public function show(AgendaItemCategory $agendaItemCategory): View
    {
        return view('beheer.agendaItemCategory.show', ['agendaItemCategory' => $agendaItemCategory]);
    }

    public function edit(AgendaItemCategory $agendaItemCategory): View
    {
        return view('beheer.agendaItemCategory.create_edit', [
            'agendaItemCategory' => $agendaItemCategory,
            'fields' => [
                'title' => 'Edit category',
                'method' => 'PATCH',
                'url' => route('agendaItemCategories.update', $agendaItemCategory),
            ],
        ]);
    }

    public function update(AgendaItemCategoryRequest $request, AgendaItemCategory $agendaItemCategory): RedirectResponse
    {
        $agendaItemCategory->update($request->validated());

        return to_route('agendaItemCategories.index')->with('message', 'Events category is edited');
    }

    public function destroy(AgendaItemCategory $agendaItemCategory): RedirectResponse
    {
        $agendaItemCategory->delete();

        return to_route('agendaItemCategories.index')->with('message', 'Events category is removed');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventCategoryRequest;
use App\Models\EventCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EventCategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-event-categories'),
        ];
    }

    public function index(): View
    {
        return view('admin.event-categories.index', [
            'eventCategories' => EventCategory::all(['id', 'name']),
        ]);
    }

    public function create(): View
    {
        return view('admin.event-categories.form', ['eventCategory' => new EventCategory]);
    }

    public function store(EventCategoryRequest $request): RedirectResponse
    {
        EventCategory::query()->create($request->validated());

        return to_route('admin.event-categories.index')->with('message', 'Event category added');
    }

    public function show(EventCategory $eventCategory): View
    {
        return view('admin.event-categories.show', ['eventCategory' => $eventCategory]);
    }

    public function edit(EventCategory $eventCategory): View
    {
        return view('admin.event-categories.form', ['eventCategory' => $eventCategory]);
    }

    public function update(EventCategoryRequest $request, EventCategory $eventCategory): RedirectResponse
    {
        $eventCategory->update($request->validated());

        return to_route('admin.event-categories.index')->with('message', 'Event category edited');
    }

    public function destroy(EventCategory $eventCategory): RedirectResponse
    {
        $eventCategory->delete();

        return to_route('admin.event-categories.index')->with('message', 'Event category removed');
    }
}

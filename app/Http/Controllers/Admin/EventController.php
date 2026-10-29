<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\RegistrationForm;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EventController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage-events'),
        ];
    }

    public function index(Request $request): View
    {
        $request->validate(['start_date' => ['nullable', 'date']]);

        $startDate = $request->date('start_date', tz: config('app.display_timezone')) ?? today(config('app.display_timezone'));

        return view('admin.events.index', [
            'startDate' => $startDate->toDateString(),
            'events' => Event::query()->where('end_date', '>=', $startDate->copy()->utc())->oldest('start_date')->get(),
        ]);
    }

    /**
     * With ?copy=, the form starts from that event; nothing is saved until the form is.
     */
    public function create(Request $request): View
    {
        $source = $request->filled('copy') ? Event::findOrFail($request->integer('copy')) : null;

        return $this->form($source?->replicate(['created_by']) ?? new Event, $source);
    }

    public function store(EventRequest $request): RedirectResponse
    {
        $event = new Event($request->safe()->only(['title', 'body', 'short_description', 'event_category_id', 'start_date', 'end_date']))
            ->fillRegistrationSettings($request->validated());
        $event->created_by = $request->user()->id;
        $event->save();

        if ($request->hasFile('image')) {
            $this->storeImage($request, $event);
        } elseif ($source = Event::find($request->integer('copy_of'))) {
            $event->copyImageFrom($source);
        }

        return to_route('admin.events.index')->with('message', 'Event added');
    }

    public function show(Event $event): View
    {
        return view('admin.events.show', ['event' => $event]);
    }

    public function edit(Event $event): View
    {
        return $this->form($event);
    }

    public function update(EventRequest $request, Event $event): RedirectResponse
    {
        $event->fill($request->safe()->only(['title', 'body', 'short_description', 'event_category_id', 'start_date', 'end_date']))
            ->fillRegistrationSettings($request->validated())
            ->save();
        $this->storeImage($request, $event);

        return to_route('admin.events.index')->with('message', 'Event edited');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return to_route('admin.events.index')->with('message', 'Event removed');
    }

    private function form(Event $event, ?Event $copyOf = null): View
    {
        return view('admin.events.form', [
            'event' => $event,
            'copyOf' => $copyOf,
            'eventCategories' => EventCategory::query()->orderBy('name')->pluck('name', 'id')->all(),
            'registrationForms' => RegistrationForm::query()->orderBy('name')->pluck('name', 'id')->all(),
        ]);
    }

    private function storeImage(Request $request, Event $event): void
    {
        if ($request->hasFile('image')) {
            $event->addMediaFromRequest('image')->toMediaCollection('image');
        }
    }
}

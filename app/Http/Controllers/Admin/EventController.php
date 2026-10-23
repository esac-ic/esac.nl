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
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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

    public function create(): View
    {
        return $this->form(new Event);
    }

    public function store(EventRequest $request): RedirectResponse
    {
        $event = new Event($request->validated())->fillRegistrationSettings($request->validated());
        $event->image_path = '';
        $event->created_by = $request->user()->id;
        $event->save();
        $this->storeImage($request, $event);

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

        if ($event->image_path != '') {
            Storage::disk('public')->delete($event->image_path);
        }

        return to_route('admin.events.index')->with('message', 'Event removed');
    }

    public function copy(Request $request, Event $event): RedirectResponse
    {
        $copy = $event->copyFor($request->user());

        return to_route('admin.events.edit', $copy);
    }

    private function form(Event $event): View
    {
        return view('admin.events.form', [
            'event' => $event,
            'eventCategories' => EventCategory::query()->orderBy('name')->pluck('name', 'id')->all(),
            'registrationForms' => RegistrationForm::query()->orderBy('name')->pluck('name', 'id')->all(),
        ]);
    }

    /**
     * Stores the uploaded image, cropped to 400x300, under the event's id.
     */
    private function storeImage(Request $request, Event $event): void
    {
        if ($request->hasFile('image')) {
            $name = $event->id.'-thumbnail.'.$request->image->extension();
            $request->file('image')->storeAs('agendaItem', $name, 'public');
            $event->image_path = 'agendaItem/'.$name;
            $event->save();
            $imagePath = Storage::disk('public')->path($event->image_path);
            ImageManager::usingDriver(Driver::class)->decodePath($imagePath)->cover(400, 300)->save($imagePath);
        }
    }
}

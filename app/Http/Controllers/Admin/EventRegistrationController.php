<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EventRegistrationsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRegistrationRequest;
use App\Http\Resources\RegistrationFormFieldResource;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * The registrations of an event, managed by the event administrators.
 */
class EventRegistrationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage-registrations'),
        ];
    }

    public function index(Event $event): View
    {
        return view('admin.events.registrations.index', [
            'event' => $event,
            // Anonymised registrations still count, but have no one to list
            'registrations' => $event->registrations()->whereNotNull('user_id')->with('user')->get(),
        ]);
    }

    public function create(Event $event): View
    {
        $registrationForm = $event->registrationForm->load('fields.options');

        $users = User::query()->active()
            ->whereNotIn('id', $event->registrations()->select('user_id'))
            ->orderBy('first_name')->orderBy('last_name')
            ->get(['id', 'first_name', 'preposition', 'last_name'])
            ->mapWithKeys(fn (User $user) => [$user->id => $user->name]);

        return view('admin.events.registrations.create', [
            'fields' => RegistrationFormFieldResource::collection($registrationForm->fields),
            'registrationForm' => $registrationForm,
            'users' => $users->all(),
            'event' => $event,
        ]);
    }

    public function store(AdminRegistrationRequest $request, Event $event): RedirectResponse
    {
        $event->register($request->validated('user_id'), $request->safe()->except('user_id'));

        return to_route('admin.events.registrations.index', $event);
    }

    public function show(Event $event, Registration $registration): View
    {
        return view('admin.events.registrations.show', [
            'answers' => $registration->answers()->with('field')->get(),
        ]);
    }

    public function destroy(Event $event, Registration $registration): RedirectResponse
    {
        $registration->delete();

        return back();
    }

    public function export(Event $event): StreamedResponse
    {
        return new EventRegistrationsExport($event)->download(Str::slug($event->title).'.xlsx');
    }
}

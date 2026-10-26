<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\RegistrationFormFieldResource;
use App\Models\Event;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function create(Request $request, Event $event): View|RedirectResponse
    {
        // The back button returns here after registering; the event page already shows the registration.
        if ($event->hasRegistrationFrom($request->user())) {
            return to_route('events.show', $event);
        }

        if ($error = $this->registrationError($event, $request->user())) {
            return to_route('events.show', $event)->with('error', $error);
        }

        $registrationForm = $event->registrationForm->load('fields.options');

        return view('registrations.create', [
            'fields' => RegistrationFormFieldResource::collection($registrationForm->fields),
            'action' => route('events.registration.store', $event),
            'registrationForm' => $registrationForm,
            'cancelUrl' => route('events.show', $event),
            'pageTitle' => $registrationForm->name,
        ]);
    }

    public function store(RegistrationRequest $request, Event $event): RedirectResponse
    {
        if ($error = $this->registrationError($event, $request->user())) {
            return to_route('events.show', $event)->with('error', $error);
        }

        $event->register($request->user()->id, $request->validated());

        return to_route('events.show', $event)->with('message', 'You are registered for '.$event->title.'.');
    }

    public function destroy(Request $request, Event $event): RedirectResponse
    {
        if (! $event->isRegistrationOpen()) {
            return back()->with('error', 'The registration deadline has passed, so you can no longer unregister.');
        }

        $event->registrations()->whereBelongsTo($request->user())->delete();

        return back()->with('message', 'You are no longer registered for the event.');
    }

    /**
     * Why the member can't register for the event, or null when they can.
     */
    private function registrationError(Event $event, User $user): ?string
    {
        return match (true) {
            ! $event->isRegistrationOpen() => 'The registration deadline has passed, so you can no longer register.',
            $event->registration_form_id === null => 'This event has no registration form.',
            $event->hasRegistrationFrom($user) => 'You are already registered for this event.',
            default => null,
        };
    }
}

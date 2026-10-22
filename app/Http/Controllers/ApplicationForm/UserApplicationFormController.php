<?php

namespace App\Http\Controllers\ApplicationForm;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\ApplicationFormRowVueResource;
use App\Models\AgendaItem;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class UserApplicationFormController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function showRegistrationForm(Request $request, AgendaItem $agendaItem): View|RedirectResponse
    {
        if ($error = $this->registrationError($agendaItem, $request->user())) {
            return to_route('agenda.detail', $agendaItem)->with('error', $error);
        }

        $applicationForm = $agendaItem->getApplicationForm;

        return view('forms.inschrijven', [
            'rows' => ApplicationFormRowVueResource::collection($applicationForm->applicationFormRows),
            'route' => route('forms.store', $agendaItem),
            'applicationForm' => $applicationForm,
            'cancleRoute' => route('agenda.detail', $agendaItem),
            'curPageName' => $applicationForm->name,
        ]);
    }

    public function store(RegistrationRequest $request, AgendaItem $agendaItem): RedirectResponse
    {
        if ($error = $this->registrationError($agendaItem, $request->user())) {
            return to_route('agenda.detail', $agendaItem)->with('error', $error);
        }

        $agendaItem->register($request->user()->id, $request->validated());

        return to_route('agenda.detail', $agendaItem);
    }

    public function unregister(Request $request, AgendaItem $agendaItem): RedirectResponse
    {
        if (! $agendaItem->canRegister()) {
            return back()->with('message', 'The registration date has expired, so you can no longer unsubscribe.');
        }

        ApplicationResponse::query()
            ->where('agenda_id', $agendaItem->id)
            ->where('user_id', $request->user()->id)
            ->delete();

        return back()->with('message', 'You successfully unsubscribed from the event.');
    }

    /**
     * Why the user can't register for the event, or null when they can.
     */
    private function registrationError(AgendaItem $agendaItem, User $user): ?string
    {
        return match (true) {
            ! $agendaItem->canRegister() => 'The registration date has expired, so you can no longer subscribe.',
            $agendaItem->application_form_id === null => 'ERROR: No registration form available for this activity.',
            $agendaItem->getApplicationFormResponses()->where('user_id', $user->id)->exists() => 'You have already signed up for this event.',
            default => null,
        };
    }
}

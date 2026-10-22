<?php

namespace App\Http\Controllers\ApplicationForm;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationFormRowVueResource;
use App\Models\AgendaItem;
use App\Models\ApplicationForm\ApplicationResponse;
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

    public function showRegistrationForm(Request $request, AgendaItem $agendaItem): View
    {
        if (! $agendaItem->canRegister()) {
            return $this->error('The registration date has expired, so you can no longer subscribe.');
        }

        $applicationForm = $agendaItem->getApplicationForm;
        if ($applicationForm == null) {
            return $this->error('ERROR: No registration form available for this activity.');
        }

        $alreadySignedUp = ApplicationResponse::where('user_id', $request->user()->id)
            ->where('inschrijf_form_id', $applicationForm->id)
            ->where('agenda_id', $agendaItem->id)
            ->exists();

        if ($alreadySignedUp) {
            return $this->error('You have already signed up for this event.');
        }

        return view('forms.inschrijven', [
            'rows' => ApplicationFormRowVueResource::collection($applicationForm->applicationFormRows),
            'route' => route('forms.store', $agendaItem),
            'applicationForm' => $applicationForm,
            'cancleRoute' => route('agenda.detail', $agendaItem),
            'curPageName' => $applicationForm->name,
        ]);
    }

    public function store(Request $request, AgendaItem $agendaItem): View|RedirectResponse
    {
        $alreadySignedUp = ApplicationResponse::where('user_id', $request->user()->id)
            ->where('inschrijf_form_id', $agendaItem->application_form_id)
            ->where('agenda_id', $agendaItem->id)
            ->exists();

        if ($alreadySignedUp) {
            return $this->error('You have already signed up for this event.');
        }

        $agendaItem->register($request->user()->id, $request->except(['_token']));

        return to_route('agenda.detail', $agendaItem);
    }

    public function unregister(Request $request, AgendaItem $agendaItem, int $fromAgendaItem = 1): RedirectResponse
    {
        // 0 means the member unregistered from the agenda overview.
        $redirect = $fromAgendaItem == 1 ? to_route('agenda.detail', $agendaItem) : to_route('front-end.agenda');

        if (! $agendaItem->canRegister()) {
            return $redirect->with('message', 'The registration date has expired, so you can no longer unsubscribe.');
        }

        ApplicationResponse::query()
            ->where('agenda_id', $agendaItem->id)
            ->where('user_id', $request->user()->id)
            ->delete();

        return $redirect->with('message', 'You successfully unsubscribed from the event.');
    }

    private function error(string $error): View
    {
        return view('forms.inschrijven_error', [
            'error' => $error,
            'curPageName' => $error,
        ]);
    }
}

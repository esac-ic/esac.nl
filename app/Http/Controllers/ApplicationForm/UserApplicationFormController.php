<?php

namespace App\Http\Controllers\ApplicationForm;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationFormRowVueResource;
use App\Models\AgendaItem;
use App\Models\ApplicationForm\ApplicationResponse;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserApplicationFormController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function showRegistrationForm(AgendaItem $agendaItem): View
    {
        $user_id = Auth::user()->id;

        //check if the date has expired
        if ($agendaItem->subscription_end_date < Carbon::now()) {
            $error = 'The registration date has expired, so you can no longer subscribe.';
            $curPageName = $error;

            return view("forms.inschrijven_error", compact('error', 'curPageName'));
        }

        $applicationForm = $agendaItem->getApplicationForm;
        if ($applicationForm == null) {
            $error = 'ERROR: No registration form available for this activity.';
            $curPageName = $error;

            return view("forms.inschrijven_error", compact('error', 'curPageName'));
        }

        //check if the user has already signed up
        $signup = ApplicationResponse::where('user_id', $user_id)
            ->where('inschrijf_form_id', $applicationForm->id)
            ->where('agenda_id', $agendaItem->id)
            ->first();

        if ($signup != null) {
            $error = 'You have already signed up for this event.';
            $curPageName = $error;

            return view("forms.inschrijven_error", compact('error', 'curPageName'));
        }

        //retrieves all the rows of the form
        $rows = ApplicationFormRowVueResource::collection($applicationForm->applicationFormRows);
        $route = route('forms.store', $agendaItem); //route for the form sign up
        $cancleRoute = route('agenda.detail', $agendaItem); //route for the cancel button

        $curPageName = $applicationForm->name;

        return view(
            "forms.inschrijven",
            compact('rows', 'route', 'applicationForm', 'cancleRoute', 'curPageName')
        );
    }

    public function store(Request $request, AgendaItem $agendaItem): View|RedirectResponse
    {
        //check if the user has already signed up
        $signup = ApplicationResponse::where('user_id', Auth::user()->id)
            ->where('inschrijf_form_id', $agendaItem->application_form_id)
            ->where('agenda_id', $agendaItem->id)
            ->first();

        if ($signup != null) {
            $error = 'You have already signed up for this event.';
            $curPageName = $error;

            return view("forms.inschrijven_error", compact('error', 'curPageName'));
        }

        $agendaItem->register(Auth::user()->id, $request->except(['_token']));
        $user = Auth::user();

        return to_route('agenda.detail', $agendaItem);
    }

    public function unregister(AgendaItem $agendaItem, int $fromAgendaItem = 1): RedirectResponse
    {
        if ($agendaItem->subscription_end_date < Carbon::now()) {
            Session::flash("message", 'The registration date has expired, so you can no longer unsubscribe.');

            if ($fromAgendaItem == 1) {
                return to_route('agenda.detail', $agendaItem);
            } else if ($fromAgendaItem == 0) {
                return to_route('front-end.agenda');
            }
        }

        ApplicationResponse::query()
            ->where('agenda_id', $agendaItem->id)
            ->where('user_id', Auth::user()->id)
            ->delete();

        Session::flash("message", 'You successfully unsubscribed from the event.');

        if ($fromAgendaItem == 1) {
            return to_route('agenda.detail', $agendaItem);
        } else {
            return to_route('front-end.agenda');
        }
    }
}

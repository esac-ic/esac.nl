<?php

namespace App\Http\Controllers\ApplicationForm;

use App\AgendaItem;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationFormRowVueResource;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Repositories\ApplicationFormRepositories\ApplicationFormRegistrationRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserApplicationFormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showRegistrationForm(AgendaItem $agendaItem)
    {
        $user_id = Auth::user()->id;

        //check if the date has expired
        $agendaEndDate = new \DateTime($agendaItem->subscription_endDate);
        $now = new \DateTime();
        if ($agendaEndDate < $now) {
            $error = trans("forms.signupexpired");
            $curPageName = $error;

            return view("forms.inschrijven_error", compact('error', 'curPageName'));
        }

        $applicationForm = $agendaItem->getApplicationForm;
        if ($applicationForm == null) {
            $error = trans("forms.form_not_available");
            $curPageName = $error;

            return view("forms.inschrijven_error", compact('error', 'curPageName'));
        }

        //check if the user has already signed up
        $signup = ApplicationResponse::where('user_id', $user_id)
            ->where('inschrijf_form_id', $applicationForm->id)
            ->where('agenda_id', $agendaItem->id)
            ->first();

        if ($signup != null) {
            $error = trans("forms.duplicatesignup");
            $curPageName = $error;

            return view("forms.inschrijven_error", compact('error', 'curPageName'));
        }

        //retrieves all the rows of the form
        $rows = ApplicationFormRowVueResource::collection($applicationForm->applicationFormRows);
        $route = 'forms/' . $agendaItem->id; //route for the form sign up
        $cancleRoute = 'agenda/' . $agendaItem->id; //route for the form sign up

        $curPageName = $applicationForm->name;

        return view(
            "forms.inschrijven",
            compact('rows', 'route', 'applicationForm', 'cancleRoute', 'curPageName')
        );
    }

    public function store(Request $request, AgendaItem $agendaItem, ApplicationFormRegistrationRepository $repository)
    {
        //check if the user has already signed up
        $signup = ApplicationResponse::where('user_id', Auth::user()->id)
            ->where('inschrijf_form_id', $agendaItem->application_form_id)
            ->where('agenda_id', $agendaItem->id)
            ->first();

        if ($signup != null) {
            $error = trans("forms.duplicatesignup");
            $curPageName = $error;

            return view("forms.inschrijven_error", compact('menu', 'error', 'curPageName'));
        }

        $repository->storeRegistration($request->except(['_token']), $agendaItem, Auth::user()->id);
        $user = Auth::user();

        return redirect('agenda/' . $agendaItem->id);
    }

    public function unregister(AgendaItem $agendaItem, int $fromAgendaItem = 1): RedirectResponse
    {
        if (Carbon::parse($agendaItem->subscription_endDate) < Carbon::now()) {
            Session::flash("message", trans('ApplicationForm.subscriptionDatePastUnregisterFailed'));

            if ($fromAgendaItem == 1) {
                return redirect('agenda/' . $agendaItem->id);
            } else if ($fromAgendaItem == 0) {
                return redirect('agenda/');
            }
        }

        ApplicationResponse::query()
            ->where('agenda_id', $agendaItem->id)
            ->where('user_id', Auth::user()->id)
            ->delete();

        Session::flash("message", trans('ApplicationForm.userUnregisterd'));

        if ($fromAgendaItem == 1) {
            return redirect('agenda/' . $agendaItem->id);
        } else if ($fromAgendaItem == 0) {
            return redirect('agenda/');
        }
    }
}

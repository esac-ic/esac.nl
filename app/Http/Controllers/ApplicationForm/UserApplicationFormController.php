<?php

namespace App\Http\Controllers\ApplicationForm;

use App\AgendaItem;
use App\Http\Resources\ApplicationFormRowVueResource;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Notifications\AgendaSubscribed;
use App\Repositories\ApplicationFormRepositories\ApplicationFormRegistrationRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class UserApplicationFormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param AgendaItem $agendaItem
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(AgendaItem $agendaItem)
    {
        $user_id = Auth::user()->id;

        //check if the date has expired
        $agendaEndDate = new \DateTime($agendaItem->subscription_endDate);
        $now           = new \DateTime();
        if ($agendaEndDate < $now) {
            $error       = ("forms.signupexpired");
            $curPageName = $error;

            return view("forms.inschrijven_error", compact('error', 'curPageName'));
        }

        $applicationForm = $agendaItem->getApplicationForm;
        if ($applicationForm == null) {
            $error       = ("forms.form_not_available");
            $curPageName = $error;

            return view("forms.inschrijven_error", compact('error', 'curPageName'));
        }

        //check if the user has already signed up
        $signup = ApplicationResponse::where('user_id', $user_id)
            ->where('inschrijf_form_id', $applicationForm->id)
            ->where('agenda_id', $agendaItem->id)
            ->first();

        if ($signup != null) {
            $error       = ("forms.duplicatesignup");
            $curPageName = $error;

            return view("forms.inschrijven_error", compact('error', 'curPageName'));
        }

        //retrieves all the rows of the form
        $rows        = ApplicationFormRowVueResource::collection($applicationForm->applicationFormRows);
        $route       = 'forms/' . $agendaItem->id; //route for the form sign up
        $cancleRoute = 'agenda/' . $agendaItem->id; //route for the form sign up

        $curPageName = $applicationForm->applicationFormName->text();

        return view(
            "forms.inschrijven",
            compact('rows', 'route', 'applicationForm', 'cancleRoute', 'curPageName')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param AgendaItem $agendaItem
     * @param ApplicationFormRegistrationRepository $repository
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, AgendaItem $agendaItem, ApplicationFormRegistrationRepository $repository)
    {
        //check if the user has already signed up
        $signup = ApplicationResponse::where('user_id', Auth::user()->id)
            ->where('inschrijf_form_id', $agendaItem->application_form_id)
            ->where('agenda_id', $agendaItem->id)
            ->first();

        if ($signup != null) {
            $error       = ("forms.duplicatesignup");
            $curPageName = $error;

            return view("forms.inschrijven_error", compact('menu', 'error', 'curPageName'));
        }

        $repository->storeRegistration($request->except(['_token']), $agendaItem, Auth::user()->id);
        $user = Auth::user();
        $user->notify(new AgendaSubscribed($agendaItem));

        return redirect('agenda/' . $agendaItem->id);
    }

    /**
     * @param AgendaItem $agendaItem
     * @return RedirectResponse
     */
    public function unregister(AgendaItem $agendaItem): RedirectResponse
    {
        if (Carbon::parse($agendaItem->subscription_endDate) < Carbon::now()) {
            Session::flash("message",('ApplicationForm.subscriptionDatePastUnregisterFailed'));

            return redirect('agenda/' . $agendaItem->id);
        }

        ApplicationResponse::query()
            ->where('agenda_id', $agendaItem->id)
            ->where('user_id', Auth::user()->id)
            ->delete();

        Session::flash("message",('ApplicationForm.userUnregisterd'));

        return redirect('agenda/' . $agendaItem->id);
    }
}

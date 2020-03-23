<?php

namespace App\Http\Controllers;

use App\AgendaItem;
use App\Exports\AgendaRegistrationExport;
use App\repositories\InschrijvenRepository;
use App\User;
use App\Notifications\AgendaSubscribed;
use Illuminate\Http\Request;
use App\CustomClasses\MenuSingleton;
use App\repositories\RepositorieFactory;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class InschrijfController extends Controller
{

/*
 * todo show store application information
 */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $_menu;
    private $_MenuItemRepository;
    private $_ApplicationResponse;
    private $_InschrijvenRepository;
    private $_userRepository;

    public function __construct(MenuSingleton $menuSingleton, RepositorieFactory $repositorieFactory)
    {
        $this->middleware('auth');
        $this->middleware('authorize:'.\Config::get('constants.Content_administrator') .',' . \Config::get('constants.Activity_administrator'))
            ->except([
                'showPersonalRegistrationForm',
                'savePersonalRegistrationForm',
                'destroy',
                'showApplicationFormInformation',
            ]);

        $this->_menu = $menuSingleton;
        $this->_MenuItemRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$MENUREPOKEY);
        //$this->_ApplicationResponse = new ApplicationResponse();
        $this->_InschrijvenRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$INSCHRIJVENREPOKEY);
        $this->_userRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$USERREPOKEY);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showPersonalRegistrationForm(AgendaItem $agendaItem)
    {
        $menu = $this->_menu;
        $user_id = Auth::user()->id;

        //check if the date has expired
        $agendaEndDate = new \DateTime($agendaItem->subscription_endDate);
        $now = new \DateTime();
        if($agendaEndDate < $now) {
            $error = trans("forms.signupexpired");
            $curPageName = $error;


            return view("forms.inschrijven_error", compact('menu','error','curPageName'));
        }

        $application_form = $agendaItem->getApplicationForm;
        if($application_form == null) {
            $error = trans("forms.form_not_available");
            $curPageName = $error;

            return view("forms.inschrijven_error", compact('menu','error','curPageName'));
        }

        $application_form_Id = $application_form->id; //gets application form from agenda item

        //check if the user has already signed up
        $signup = ApplicationResponse::where('user_id',$user_id)->where('inschrijf_form_id',$application_form_Id)->where('agenda_id',$agendaItem->id)->get()->first();

        if($signup != null) {
            $error = trans("forms.duplicatesignup");
            $curPageName = $error;

            return view("forms.inschrijven_error", compact('menu','error','curPageName'));
        }

        //retrieves all the rows of the form
        $rows = ApplicationFormRow::query()->where('application_form_id','=',$application_form->id)->get();
        $route = 'forms/'. $agendaItem->id; //route for the form sign up
        $cancleRoute = 'agenda/'. $agendaItem->id; //route for the form sign up

        $curPageName = $application_form->applicationFormName->text();

        return view("forms.inschrijven", compact('menu','rows', 'route','application_form','cancleRoute','curPageName'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function savePersonalRegistrationForm(Request $request,AgendaItem $agendaItem)
    {
        $this->_InschrijvenRepository->store($agendaItem,$request,Auth::user()->id);
        $user = Auth::user();
        $user->notify(new AgendaSubscribed($agendaItem));

        return redirect('agenda/'. $agendaItem->id);
    }

    /*Return a view to register a user */
    public function showRegistrationform(AgendaItem $agendaItem){
        //retrieves all the rows of the form
        $applicationForm = $agendaItem->getApplicationForm;
        $rows = $applicationForm->applicationFormRows;
        $users = array();
        $registerdUsers = array();

        foreach ($this->_InschrijvenRepository->getRegisterdusers($agendaItem->id) as $registerdUser){
            array_push($registerdUsers,$registerdUser->user_id);
        }
        foreach ($this->_userRepository->getCurrentUsers(array('id','firstname','lastname')) as $user){
            if(!in_array($user->id,$registerdUsers)){
                $users[$user->id] = $user->getName();
            }
        }

        return view("forms.inschrijven_admin", compact('rows', 'applicationForm','users','agendaItem'));
    }


    public function exportUsers(int $agendaId, InschrijvenRepository $inschrijvenRepository){
        $agendaItem = AgendaItem::findOrFail($agendaId);
        return Excel::download(
            new AgendaRegistrationExport($inschrijvenRepository, $agendaItem),
            preg_replace('/[^a-zA-Z0-9]+/', '-', $agendaItem->agendaItemTitle->text()) . '.xlsx'
        );

    }

}

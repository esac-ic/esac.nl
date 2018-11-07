<?php

namespace App\Http\Controllers;

use App\AgendaItem;
use App\ApplicationForm;
use App\ApplicationFormRow;
use App\ApplicationResponse;
use App\ApplicationResponseRow;
use App\User;
use App\Notifications\AgendaSubscribed;
use Illuminate\Http\Request;
use App\CustomClasses\MenuSingleton;
use App\repositories\RepositorieFactory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
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
        $this->_ApplicationResponse = new ApplicationResponse();
        $this->_InschrijvenRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$INSCHRIJVENREPOKEY);
        $this->_userRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$USERREPOKEY);
    }

    public function index(AgendaItem $agendaItem)
    {
        $users = $this->_InschrijvenRepository->getUsers($agendaItem->id);
        $agendaId = $agendaItem->id;
        return view("forms.inschrijven_show", compact('users','agendaId'));
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
        $rows = $applicationForm->getActiveApplicationFormRows;
        $route = "forms/admin/" . $agendaItem->id;
        $cancleRoute = '/forms/users/'. $agendaItem->id;
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

        return view("forms.inschrijven_admin", compact('rows', 'route','applicationForm','users','cancleRoute'));
    }

    /*Return a view to register a user */
    public function saveRegistrationform(Request $request, AgendaItem $agendaItem){
        $this->_InschrijvenRepository->store($agendaItem,$request,$request['user']);

        return redirect('/forms/users/'. $agendaItem->id);
    }

    public function showApplicationFormInformation(User $user, AgendaItem $agendaItem){
        if(!Auth::user()->hasRole(\Config::get('constants.Activity_administrator'))){
            if(Auth::user()->id != $user->id){
                abort(401);
            }
        }

        $applicationDataRows = $this->_InschrijvenRepository->getApplicationInformation($agendaItem->id,$user->id);
        $agendaId = $agendaItem->id;

        return view('forms.inschrijven_details',compact('agendaId','applicationDataRows'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($Agenda_id, $applicationResponseId)
    {
        $applicationReponse = ApplicationResponse::find($applicationResponseId);
        if(!Auth::user()->hasRole(\Config::get('constants.Activity_administrator'))){
            if(Auth::user()->id != $applicationReponse->user_id){
                abort(401);
            }
        }
        $this->_InschrijvenRepository->delete($applicationResponseId);
        return redirect('forms/users/'.$Agenda_id);
    }
    public function exportUsers($Agenda_id){
        $this->_InschrijvenRepository->exportUsers($Agenda_id);
    }

}

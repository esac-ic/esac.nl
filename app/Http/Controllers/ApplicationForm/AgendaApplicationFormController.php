<?php

namespace App\Http\Controllers\ApplicationForm;

use App\AgendaItem;
use App\Http\Resources\ApplicationFormRowVueResource;
use App\repositories\InschrijvenRepository;
use App\repositories\UserRepository;
use App\Services\AgendaApplicationFormService;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AgendaApplicationFormController extends Controller
{
    /**
     * AgendaApplicationFormController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . \Config::get('constants.Content_administrator') . ',' . \Config::get('constants.Activity_administrator'));
    }

    /**
     * @param AgendaItem $agendaItem
     * @param AgendaApplicationFormService $agendaApplicationFormService
     * @return \View
     */
    public function index(AgendaItem $agendaItem, AgendaApplicationFormService $agendaApplicationFormService): View
    {
        $users = $agendaApplicationFormService->getRegisteredUsers($agendaItem);
        $agendaId = $agendaItem->id;

        return view("forms.inschrijven_show", compact('users', 'agendaId'));
    }

    public function registerUser(
        AgendaItem $agendaItem,
        UserRepository $userRepository,
        InschrijvenRepository $registerRepository
    ): View {
        //retrieves all the rows of the form
        $applicationForm = $agendaItem->getApplicationForm;
        $rows            = ApplicationFormRowVueResource::collection($applicationForm->applicationFormRows);
        $users           = [];
        $registeredUsers = [];

        foreach ($registerRepository->getRegisterdusers($agendaItem->id) as $registeredUser) {
            array_push($registeredUsers, $registeredUser->user_id);
        }

        foreach ($userRepository->getCurrentUsers(array('id', 'firstname', 'lastname')) as $user) {
            if (!in_array($user->id, $registeredUsers)) {
                $users[$user->id] = $user->getName();
            }
        }

        return view("forms.inschrijven_admin", compact('rows', 'applicationForm', 'users', 'agendaItem'));
    }

}

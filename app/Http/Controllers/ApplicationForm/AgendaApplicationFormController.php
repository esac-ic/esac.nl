<?php

namespace App\Http\Controllers\ApplicationForm;

use App\AgendaItem;
use App\Exports\AgendaRegistrationExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationFormRowVueResource;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Repositories\ApplicationFormRepositories\ApplicationFormRegistrationRepository;
use App\Repositories\UserRepository;
use App\Services\AgendaApplicationFormService;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AgendaApplicationFormController extends Controller
{
    /**
     * AgendaApplicationFormController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Content_administrator') . ',' . Config::get('constants.Activity_administrator'))->except('destroy');
    }

    /**
     * @param AgendaItem $agendaItem
     * @param AgendaApplicationFormService $agendaApplicationFormService
     * @return \View
     */
    public function index(AgendaItem $agendaItem, AgendaApplicationFormService $agendaApplicationFormService): View
    {
        $users = $agendaApplicationFormService->getRegisteredUsers($agendaItem);
        $agendaItemId = $agendaItem->id;

        return view("forms.inschrijven_show", compact('users', 'agendaItemId'));
    }

    public function registerUser(AgendaItem $agendaItem, UserRepository $userRepository): View
    {
        //retrieves all the rows of the form
        $applicationForm = $agendaItem->getApplicationForm;
        $rows = ApplicationFormRowVueResource::collection($applicationForm->applicationFormRows);
        $users = [];
        $registeredUsers = [];

        foreach ($agendaItem->getApplicationFormResponses as $registeredUser) {
            array_push($registeredUsers, $registeredUser->user_id);
        }

        foreach ($userRepository->getCurrentUsers(array('id', 'firstname', 'lastname')) as $user) {
            if (!in_array($user->id, $registeredUsers)) {
                $users[$user->id] = $user->getName();
            }
        }
        return view("forms.inschrijven_admin", compact('rows', 'applicationForm', 'users', 'agendaItem'));
    }

    /**
     * @param Request $request
     * @param AgendaItem $agendaItem
     * @param ApplicationFormRegistrationRepository $repository
     * @return RedirectResponse
     */
    public function saveRegistration(
        Request $request,
        AgendaItem $agendaItem,
        ApplicationFormRegistrationRepository $repository
    ): RedirectResponse {
        $repository->storeRegistration($request->except(['_token', 'user']), $agendaItem, $request['user']);

        return redirect('/forms/users/' . $agendaItem->id);
    }

    /**
     * @param User $user
     * @param AgendaItem $agendaItem
     * @param ApplicationFormRegistrationRepository $repository
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function show(User $user, AgendaItem $agendaItem, ApplicationFormRegistrationRepository $repository): View
    {
        $applicationDataRows = $repository->getApplicationInformation($agendaItem->id, $user->id);
        $agendaId = $agendaItem->id;

        return view('forms.inschrijven_details', compact('agendaId', 'applicationDataRows'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $Agenda_id
     * @param $applicationResponseId
     * @return \Illuminate\Http\Response
     */
    public function destroy($Agenda_id, $applicationResponseId): RedirectResponse
    {
        $applicationReponse = ApplicationResponse::find($applicationResponseId);
        if (!Auth::user()->hasRole(Config::get('constants.Activity_administrator'))) {
            if (Auth::user()->id != $applicationReponse->user_id) {
                abort(401);
            }
        }

        ApplicationResponse::destroy($applicationResponseId);
        return redirect('forms/users/' . $Agenda_id);
    }

    /**
     * @param int $agendaId
     * @param AgendaApplicationFormService $agendaApplicationFormService
     * @return BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportData(int $agendaId, AgendaApplicationFormService $agendaApplicationFormService): BinaryFileResponse
    {
        $agendaItem = AgendaItem::findOrFail($agendaId);
        return Excel::download(
            new AgendaRegistrationExport($agendaApplicationFormService, $agendaItem),
            preg_replace('/[^a-zA-Z0-9]+/', '-', $agendaItem->title) . '.xlsx'
        );

    }
}

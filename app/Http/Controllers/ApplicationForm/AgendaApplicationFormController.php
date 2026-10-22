<?php

namespace App\Http\Controllers\ApplicationForm;

use App\Exports\AgendaRegistrationExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationFormRowVueResource;
use App\Models\AgendaItem;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\User;
use App\Repositories\ApplicationFormRepositories\ApplicationFormRegistrationRepository;
use App\Repositories\UserRepository;
use App\Services\AgendaApplicationFormService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AgendaApplicationFormController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-agenda', except: ['destroy']),
        ];
    }

    /**
     * @param AgendaItem $agendaItem
     * @param AgendaApplicationFormService $agendaApplicationFormService
     * @return View
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

        foreach ($userRepository->getCurrentUsers(['id', 'firstname', 'lastname']) as $user) {
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

        return to_route('forms.users.index', $agendaItem);
    }

    /**
     * @param User $user
     * @param AgendaItem $agendaItem
     * @param ApplicationFormRegistrationRepository $repository
     * @return View
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
     * @return Response
     */
    public function destroy($Agenda_id, $applicationResponseId): RedirectResponse
    {
        $registration = ApplicationResponse::findOrFail($applicationResponseId);
        Gate::authorize('delete-registration', $registration);

        $registration->delete();
        return to_route('forms.users.index', $Agenda_id);
    }

    /**
     * @param int $agendaId
     * @param AgendaApplicationFormService $agendaApplicationFormService
     * @return StreamedResponse
     */
    public function exportData(int $agendaId, AgendaApplicationFormService $agendaApplicationFormService): StreamedResponse
    {
        $agendaItem = AgendaItem::findOrFail($agendaId);
        return new AgendaRegistrationExport($agendaApplicationFormService, $agendaItem)
            ->download(preg_replace('/[^a-zA-Z0-9]+/', '-', $agendaItem->title) . '.xlsx');
    }
}

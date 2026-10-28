<?php

namespace App\Http\Controllers\ApplicationForm;

use App\Exports\AgendaRegistrationExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRegistrationRequest;
use App\Http\Resources\ApplicationFormRowVueResource;
use App\Models\AgendaItem;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\ApplicationForm\ApplicationResponseRow;
use App\Models\User;
use App\Services\AgendaApplicationFormService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
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

    public function index(AgendaItem $agendaItem, AgendaApplicationFormService $agendaApplicationFormService): View
    {
        return view('forms.inschrijven_show', [
            'users' => $agendaApplicationFormService->getRegisteredUsers($agendaItem),
            'agendaItemId' => $agendaItem->id,
        ]);
    }

    public function registerUser(AgendaItem $agendaItem): View
    {
        $applicationForm = $agendaItem->getApplicationForm;
        $users = [];
        $registeredUsers = [];

        foreach ($agendaItem->getApplicationFormResponses as $registeredUser) {
            array_push($registeredUsers, $registeredUser->user_id);
        }

        foreach (User::current()->orderBy('firstname')->orderBy('lastname')->get(['id', 'firstname', 'preposition', 'lastname']) as $user) {
            if (! in_array($user->id, $registeredUsers)) {
                $users[$user->id] = $user->getName();
            }
        }

        return view('forms.inschrijven_admin', [
            'rows' => ApplicationFormRowVueResource::collection($applicationForm->applicationFormRows),
            'applicationForm' => $applicationForm,
            'users' => $users,
            'agendaItem' => $agendaItem,
        ]);
    }

    public function saveRegistration(AdminRegistrationRequest $request, AgendaItem $agendaItem): RedirectResponse
    {
        $agendaItem->register($request->validated('user'), $request->safe()->except('user'));

        return to_route('forms.users.index', $agendaItem);
    }

    public function show(User $user, AgendaItem $agendaItem): View
    {
        $applicationResponse = ApplicationResponse::query()
            ->where('agenda_id', $agendaItem->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('forms.inschrijven_details', [
            'agendaId' => $agendaItem->id,
            'applicationDataRows' => ApplicationResponseRow::where('application_response_id', $applicationResponse->id)->get(),
        ]);
    }

    public function destroy(AgendaItem $agendaItem, ApplicationResponse $applicationResponse): RedirectResponse
    {
        Gate::authorize('delete-registration', $applicationResponse);

        $applicationResponse->delete();

        return to_route('forms.users.index', $agendaItem);
    }

    public function exportData(AgendaItem $agendaItem, AgendaApplicationFormService $agendaApplicationFormService): StreamedResponse
    {
        return new AgendaRegistrationExport($agendaApplicationFormService, $agendaItem)
            ->download(preg_replace('/[^a-zA-Z0-9]+/', '-', $agendaItem->title).'.xlsx');
    }
}

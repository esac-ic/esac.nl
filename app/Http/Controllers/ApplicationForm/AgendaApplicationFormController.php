<?php

namespace App\Http\Controllers\ApplicationForm;

use App\Exports\AgendaRegistrationExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRegistrationRequest;
use App\Http\Resources\ApplicationFormRowResource;
use App\Models\AgendaItem;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * The registrations of an event, managed by the activity administrators.
 */
class AgendaApplicationFormController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-agenda', except: ['destroy']),
        ];
    }

    public function index(AgendaItem $agendaItem): View
    {
        return view('admin.registrations.index', [
            'agendaItem' => $agendaItem,
            // Anonymised registrations still count, but have no one to list
            'registrations' => $agendaItem->registrations()->whereNotNull('user_id')->with('user')->get(),
        ]);
    }

    public function create(AgendaItem $agendaItem): View
    {
        $applicationForm = $agendaItem->applicationForm->load('applicationFormRows.applicationFormRowOptions');

        $users = User::query()->current()
            ->whereNotIn('id', $agendaItem->registrations()->select('user_id'))
            ->orderBy('firstname')->orderBy('lastname')
            ->get(['id', 'firstname', 'preposition', 'lastname'])
            ->mapWithKeys(fn (User $user) => [$user->id => $user->getName()]);

        return view('admin.registrations.create', [
            'rows' => ApplicationFormRowResource::collection($applicationForm->applicationFormRows),
            'applicationForm' => $applicationForm,
            'users' => $users->all(),
            'agendaItem' => $agendaItem,
        ]);
    }

    public function store(AdminRegistrationRequest $request, AgendaItem $agendaItem): RedirectResponse
    {
        $agendaItem->register($request->validated('user'), $request->safe()->except('user'));

        return to_route('admin.agenda-items.registrations.index', $agendaItem);
    }

    public function show(AgendaItem $agendaItem, ApplicationResponse $registration): View
    {
        return view('admin.registrations.show', [
            'rows' => $registration->rows()->with('formRow')->get(),
        ]);
    }

    public function destroy(AgendaItem $agendaItem, ApplicationResponse $registration): RedirectResponse
    {
        Gate::authorize('delete-registration', $registration);

        $registration->delete();

        return to_route('admin.agenda-items.registrations.index', $agendaItem);
    }

    public function export(AgendaItem $agendaItem): StreamedResponse
    {
        return new AgendaRegistrationExport($agendaItem)->download(Str::slug($agendaItem->title).'.xlsx');
    }
}

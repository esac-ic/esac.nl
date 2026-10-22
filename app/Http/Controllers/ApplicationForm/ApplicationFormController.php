<?php

namespace App\Http\Controllers\ApplicationForm;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationFormStoreRequest;
use App\Http\Resources\ApplicationFormRowResource;
use App\Models\ApplicationForm\ApplicationForm;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class ApplicationFormController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-agenda'),
        ];
    }

    public function index(): View
    {
        return view('beheer.applicationForm.index', [
            'applicationForms' => ApplicationForm::all(),
        ]);
    }

    public function create(): View
    {
        return $this->form(new ApplicationForm);
    }

    public function store(ApplicationFormStoreRequest $request): RedirectResponse
    {
        DB::transaction(fn () => ApplicationForm::query()->create($request->safe()->only('name'))->syncRows($request->validated('rows', [])));

        return to_route('beheer.application-forms.index')->with('message', 'Application form added');
    }

    public function show(ApplicationForm $applicationForm): View
    {
        return view('beheer.applicationForm.show', ['applicationForm' => $applicationForm->load('applicationFormRows.applicationFormRowOptions')]);
    }

    public function edit(ApplicationForm $applicationForm): View
    {
        return $this->form($applicationForm);
    }

    public function update(ApplicationFormStoreRequest $request, ApplicationForm $applicationForm): RedirectResponse
    {
        DB::transaction(function () use ($request, $applicationForm) {
            $applicationForm->update($request->safe()->only('name'));
            $applicationForm->syncRows($request->validated('rows', []));
        });

        return to_route('beheer.application-forms.index')->with('message', 'Application form edited');
    }

    public function destroy(ApplicationForm $applicationForm): RedirectResponse
    {
        $applicationForm->applicationFormRows->each->delete();
        $applicationForm->delete();

        return to_route('beheer.application-forms.index')->with('message', 'Application form removed');
    }

    private function form(ApplicationForm $applicationForm): View
    {
        return view('beheer.applicationForm.create_edit', [
            'applicationForm' => $applicationForm->load('applicationFormRows.applicationFormRowOptions'),
            'rows' => ApplicationFormRowResource::collection($applicationForm->applicationFormRows),
        ]);
    }
}

<?php

namespace App\Http\Controllers\ApplicationForm;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationFormStoreRequest;
use App\Http\Resources\ApplicationFormRowVueResource;
use App\Models\ApplicationForm\ApplicationForm;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

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
        ApplicationForm::create($request->validated())->syncRows($request->input('rows', []));

        return to_route('beheer.applicationForms.index')->with('message', 'Application form added');
    }

    public function show(ApplicationForm $applicationForm): View
    {
        return view('beheer.applicationForm.show', ['applicationForm' => $applicationForm]);
    }

    public function edit(ApplicationForm $applicationForm): View
    {
        return $this->form($applicationForm);
    }

    public function update(ApplicationFormStoreRequest $request, ApplicationForm $applicationForm): RedirectResponse
    {
        $applicationForm->update($request->validated());
        $applicationForm->syncRows($request->input('rows', []));

        return to_route('beheer.applicationForms.index')->with('message', 'Application form edited');
    }

    public function destroy(ApplicationForm $applicationForm): RedirectResponse
    {
        $applicationForm->applicationFormRows->each->delete();
        $applicationForm->delete();

        return to_route('beheer.applicationForms.index')->with('message', 'Application form removed');
    }

    private function form(ApplicationForm $applicationForm): View
    {
        return view('beheer.applicationForm.create_edit', [
            'applicationForm' => $applicationForm,
            'rows' => ApplicationFormRowVueResource::collection($applicationForm->applicationFormRows),
        ]);
    }
}

<?php

namespace App\Http\Controllers\ApplicationForm;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationFormStoreRequest;
use App\Http\Resources\ApplicationFormRowVueResource;
use App\Models\ApplicationForm\ApplicationForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ApplicationFormController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage-agenda'),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $applicationForms = ApplicationForm::query()
            ->get();

        return view('beheer.applicationForm.index')
            ->with([
                'applicationForms' => $applicationForms,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $fields = [
            'title' => 'Add an application form',
            'method' => 'POST',
            'url' => route('beheer.applicationForms.store'),
        ];

        return view('beheer.applicationForm.create_edit')
            ->with([
                'fields' => $fields,
                'applicationForm' => null,
                'rows' => [],
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ApplicationFormStoreRequest $request
     * @return RedirectResponse
     */
    public function store(ApplicationFormStoreRequest $request): RedirectResponse
    {
        ApplicationForm::create($request->all())->syncRows($request->input('rows', []));

        Session::flash("message", 'Application form added');
        return to_route('beheer.applicationForms.index');
    }

    /**
     * Display the specified resource.
     *
     * @param ApplicationForm $applicationForm
     * @return View
     */
    public function show(ApplicationForm $applicationForm): View
    {
        return view('beheer.applicationForm.show')
            ->with([
                'applicationForm' => $applicationForm,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ApplicationForm $applicationForm
     * @return View
     */
    public function edit(ApplicationForm $applicationForm): View
    {
        $fields = [
            'title' => 'Add an application form',
            'method' => 'PUT',
            'url' => route('beheer.applicationForms.update', $applicationForm->id),
        ];

        return view('beheer.applicationForm.create_edit')
            ->with([
                'fields' => $fields,
                'applicationForm' => $applicationForm,
                'rows' => ApplicationFormRowVueResource::collection($applicationForm->applicationFormRows),
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ApplicationFormStoreRequest $request
     * @param ApplicationForm $applicationForm
     * @return RedirectResponse
     */
    public function update(ApplicationFormStoreRequest $request, ApplicationForm $applicationForm): RedirectResponse
    {
        $applicationForm->update($request->all());
        $applicationForm->syncRows($request->input('rows', []));

        Session::flash("message", 'Application form edited');
        return to_route('beheer.applicationForms.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ApplicationForm $applicationForm
     * @return RedirectResponse
     */
    public function destroy(ApplicationForm $applicationForm): RedirectResponse
    {
        $applicationForm->applicationFormRows->each->delete();
        $applicationForm->delete();

        Session::flash("message", 'Application form edited');
        return to_route('beheer.applicationForms.index');
    }
}

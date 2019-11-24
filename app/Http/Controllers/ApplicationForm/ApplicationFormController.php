<?php

namespace App\Http\Controllers\ApplicationForm;

use App\Http\Requests\ApplicationFormStoreRequest;
use App\Http\Resources\ApplicationFormRowVueResource;
use App\Models\ApplicationForm\ApplicationForm;
use App\repositories\ApplicationFormRepository;
use App\repositories\RepositorieFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Session;

class ApplicationFormController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('authorize:'.\Config::get('constants.Content_administrator') .',' . \Config::get('constants.Activity_administrator'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $applicationForms = ApplicationForm::query()
            ->with('applicationFormName')
            ->get();

        return view('beheer.applicationForm.index')
            ->with([
                'applicationForms' => $applicationForms
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
            'title' => trans('ApplicationForm.add'),
            'method' => 'POST',
            'url' => route('beheer.applicationForms.store')
        ];

        return view('beheer.applicationForm.create_edit')
            ->with([
                'fields'=> $fields,
                'applicationForm' => null,
                'rows' => [],
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ApplicationFormStoreRequest $request
     * @param ApplicationFormRepository $applicationFormRepository
     * @return void
     */
    public function store(ApplicationFormStoreRequest $request, ApplicationFormRepository $applicationFormRepository): RedirectResponse
    {
        $applicationFormRepository->create($request->all());

        Session::flash("message",trans('ApplicationForm.added'));
        return redirect()->route('beheer.applicationForms.index');
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
     * @return void
     */
    public function edit(ApplicationForm $applicationForm): View
    {
        $fields = [
            'title' => trans('ApplicationForm.add'),
            'method' => 'POST',
            'url' => route('beheer.applicationForms.store')
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
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

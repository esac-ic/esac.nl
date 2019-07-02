<?php

namespace App\Http\Controllers\ApplicationForm;

use App\Models\ApplicationForm\ApplicationForm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ApplicationFormController extends Controller
{
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
            'url' => '/applicationForms'
        ];

        return view('beheer.applicationForm.create_edit')
            ->with([
                'fields'=> $fields,
                'applicationForm' => null
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

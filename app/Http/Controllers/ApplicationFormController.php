<?php

namespace App\Http\Controllers;

use App\ApplicationForm;
use App\ApplicationFormRow;
use App\repositories\RepositorieFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class ApplicationFormController extends Controller
{
    private $_applicationFormRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RepositorieFactory $repositorieFactory)
    {
        $this->middleware('auth');
        $this->middleware('authorize:'.\Config::get('constants.Content_administrator') .',' . \Config::get('constants.Activity_administrator'));
        $this->_applicationFormRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$APPLICATIONFORMREPOKEY);
    }

    //gives the rol views
    public function index(){
        $applicationForms = $this->_applicationFormRepository->all(array("name","id"));

        return view('beheer.applicationForm.index', compact('applicationForms'));
    }

    //show create screen
    public function create(){
        $fields = ['title' => trans('ApplicationForm.add'),
            'method' => 'POST',
            'url' => '/applicationForms',];

        $applicationForm = null;

        return view('beheer.applicationForm.create_edit',compact(['fields','applicationForm']));

    }

    //store rol
    public function store(Request $request){
        $this->validateInput($request);
        $this->_applicationFormRepository->create($request->all());

        Session::flash("message",trans('ApplicationForm.added'));
        return redirect('/applicationForms');
    }

    public function show(Request $request, ApplicationForm $applicationForm){
        return view('beheer.applicationForm.show',compact('applicationForm'));
    }

    //show edit screen
    public function edit(Request $request,ApplicationForm $applicationForm){
        $fields = ['title' => trans('ApplicationForm.edit'),
            'method' => 'PATCH',
            'url' => '/applicationForms/' . $applicationForm->id,];

        $applicationFormRows = ApplicationFormRow::query()->where('application_form_id','=',$applicationForm->id)->get();

        return view('beheer.applicationForm.create_edit',compact(['fields','applicationForm','applicationFormRows']));
    }

    //update application form
    public function update(Request $request,ApplicationForm $applicationForm){
        $this->validateInput($request);
        $this->_applicationFormRepository->update($applicationForm->id, $request->all());

        Session::flash("message",trans('ApplicationForm.edited'));
        return redirect('/applicationForms');
    }

    public function destroy(Request $request, ApplicationForm $applicationForm){
        $this->_applicationFormRepository->delete($applicationForm->id);

        Session::flash("message",trans('ApplicationForm.deleted'));
        return redirect('/applicationForms');
    }

    private function validateInput(Request $request){
        $this->validate($request,[
            'EN_text' => 'required',
            'NL_text' => 'required',
            'amount_of_formrows' => 'required|numeric|min:1'
        ]);
    }
}

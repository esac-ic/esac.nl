<?php

namespace App\Http\Controllers;

use App\AgendaItemCategorie;
use App\repositories\RepositorieFactory;
use Illuminate\Http\Request;

class AgendaItemCategorieController extends Controller
{
    private $_AgendaItemCategoryRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RepositorieFactory $repositorieFactory)
    {
        $this->middleware('auth');
        $this->middleware('authorize:'.\Config::get('constants.Administrator'));
        $this->_AgendaItemCategoryRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$AGENDAITEMRECATEGORYPOKEY);
    }

    //gives the agendaItemCategory views
    public function index(){
        $agendaItemCategories= $this->_AgendaItemCategoryRepository->all(array("id","name"));
        return view('beheer.agendaItemCategory.index', compact('agendaItemCategories'));
    }

    //show create screen
    public function create(){
        $fields = ['title' => trans('agendaItemCategory.add'),
            'method' => 'POST',
            'url' => '/agendaItemCategories',];
        $agendaItemCategory = null;
        return view('beheer.agendaItemCategory.create_edit', compact(['fields','agendaItemCategory']));
    }

    //store agendaItemCategory
    public function store(Request $request){
        $this->validateInput($request);
        $this->_AgendaItemCategoryRepository->create($request->all());

        \Session::flash("message",trans('agendaItemCategory.added'));
        return redirect('/agendaItemCategories');
    }

    public function show(Request $request, AgendaItemCategorie $agendaItemCategory){
        return view('beheer.agendaItemCategory.show', compact('agendaItemCategory'));
    }

    //show edit screen
    public function edit(Request $request,AgendaItemCategorie $agendaItemCategory){
        $fields = ['title' => trans('agendaItemCategory.edit'),
            'method' => 'PATCH',
            'url' => '/agendaItemCategories/'. $agendaItemCategory->id];

        return view('beheer.agendaItemCategory.create_edit', compact('fields','agendaItemCategory'));
    }

    //update agendaItemCategory
    public function update(Request $request,AgendaItemCategorie $agendaItemCategory){
        $this->validateInput($request);
        $this->_AgendaItemCategoryRepository->update($agendaItemCategory->id,$request->all());

        \Session::flash("message",trans('agendaItemCategory.edited'));
        return redirect('/agendaItemCategories');
    }

    public function destroy(Request $request, AgendaItemCategorie $agendaItemCategory){
        $this->_AgendaItemCategoryRepository->delete($agendaItemCategory->id);

        \Session::flash("message",trans('agendaItemCategory.deleted'));
        return redirect('/agendaItemCategories');
    }

    private function validateInput(Request $request){
        $this->validate($request,[
            'EN_text' => 'required',
            'NL_text' => 'required',
        ]);
    }
}

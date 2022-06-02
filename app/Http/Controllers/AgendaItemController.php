<?php

namespace App\Http\Controllers;

use App\AgendaItem;
use App\Repositories\ApplicationFormRepositories\ApplicationFormRepository;
use App\Repositories\RepositorieFactory;
use Illuminate\Http\RedirectResponse;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;

class AgendaItemController extends Controller
{

    private $_agendaItemRepository;
    private $_agendaItemCategoryRepository;
    private $_applicationFormRepository;

    /**
     * AgendaItemController constructor.
     * @param RepositorieFactory $repositorieFactory
     * @param ApplicationFormRepository $applicationFormRepository
     */
    public function __construct(RepositorieFactory $repositorieFactory, ApplicationFormRepository $applicationFormRepository)
    {
        $this->_agendaItemRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$AGENDAITEMREPOKEY);
        $this->_agendaItemCategoryRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$AGENDAITEMRECATEGORYPOKEY);
        $this->_applicationFormRepository = $applicationFormRepository;

        $this->middleware('auth');
        $this->middleware('authorize:'.\Config::get('constants.Content_administrator') .',' . \Config::get('constants.Activity_administrator'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('beheer.agendaItem.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $fields = [
            'title_info' => trans('AgendaItems.info'),
            'title_content' => trans('AgendaItems.content'),
            'title_image' => trans('AgendaItems.imagePage'),
            'title'     => trans('AgendaItems.add'),
            'method' => 'POST',
            'url' => '/agendaItems',];

        $agendaItem = null;
        $agendaItemCategories = array();
        $applicationForms = array();

        foreach ($this->_agendaItemCategoryRepository->all(array('id','name')) as $category){
            $agendaItemCategories[$category->id] = $category->categorieName->text();
        }

        foreach ($this->_applicationFormRepository->all(array('id','name')) as $form){
            $applicationForms[$form->id] = $form->applicationFormName->text();
        }

        return view('beheer.agendaItem.create_edit',compact(['fields','agendaItem','agendaItemCategories','applicationForms']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request){
        $this->validateInput($request);
        $agendaItem = $this->_agendaItemRepository->create($request->all());

        //save image
        if($request->hasFile('thumbnail')){
            $name  = $agendaItem->id . '-thumbnail.' . $request->thumbnail->extension();
            $request->file('thumbnail')->storeAs('agendaItem', $name,'public');
            $agendaItem->image_url = 'agendaItem/' .  $name;
            $agendaItem->save();

            //resize image
            $img_path = "../storage/app/public/" . $agendaItem->image_url;
            Image::make($img_path)->fit(400, 300)->save($img_path);
        }

        \Session::flash("message",trans('AgendaItems.added'));
        return redirect('/agendaItems');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(AgendaItem $agendaItem)
    {
        return view('beheer.agendaItem.show', compact('agendaItem'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(AgendaItem $agendaItem)
    {
        $fields = [
            'title_info' => trans('AgendaItems.info'),
            'title_content' => trans('AgendaItems.content'),
            'title_image' => trans('AgendaItems.imagePage'),
            'title'     => trans('AgendaItems.edit'),
            'method' => 'PATCH',
            'url' => '/agendaItems/' . $agendaItem->id];

        $agendaItemCategories = array();
        $applicationForms = array();

        foreach ($this->_agendaItemCategoryRepository->all(array('id','name')) as $category){
            $agendaItemCategories[$category->id] = $category->categorieName->text();
        }

        foreach ($this->_applicationFormRepository->all(array('id','name')) as $form){
            $applicationForms[$form->id] = $form->applicationFormName->text();
        }

        return view('beheer.agendaItem.create_edit',compact(['fields','agendaItem','agendaItemCategories','applicationForms']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, AgendaItem $agendaItem)
    {
        $this->validateInput($request);
        $this->_agendaItemRepository->update($agendaItem->id, $request->all());

        //save image
        if($request->hasFile('thumbnail')){
            $name  = $agendaItem->id . '-thumbnail.' . $request->thumbnail->extension();
            $request->file('thumbnail')->storeAs('agendaItem', $name,'public');
            $agendaItem->image_url = 'agendaItem/' .  $name;
            $agendaItem->save();

            //resize image
            $img_path = "../storage/app/public/" . $agendaItem->image_url;
            Image::make($img_path)->fit(400, 300)->save($img_path);
        }

        \Session::flash("message",trans('AgendaItems.edited'));
        return redirect('/agendaItems');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->_agendaItemRepository->delete($id);

        \Session::flash("message",trans('AgendaItems.deleted'));
        return redirect('/agendaItems');
    }

    /**
     * @param AgendaItem $agendaItem
     * @return RedirectResponse
     */
    public function copy(AgendaItem $agendaItem): RedirectResponse
    {
        $newAgendaItem = $this->_agendaItemRepository->copy($agendaItem);

        return redirect('/agendaItems/' . $newAgendaItem->id . '/edit');
    }

    private function validateInput(Request $request){
        $this->validate($request,[
            'NL_title' => 'required',
            'EN_title' => 'required',
            'NL_text' => 'required',
            'EN_text' => 'required',
            'category' => 'required|numeric|min:1',
            'applicationForm' => 'required|numeric|min:0',
            'endDate' => 'required|date',
            'startDate' => 'required|date',
        ]);
    }
}

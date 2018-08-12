<?php

namespace App\Http\Controllers;

use App\AgendaItem;
use App\repositories\RepositorieFactory;
use Folklore\Image\Facades\Image;
use Illuminate\Http\Request;
use Imagine\Image\Box;

class AgendaItemController extends Controller
{

    private $_agendaItemRepository;
    private $_agendaItemCategoryRepository;
    private $_applicationFormRepostory;

    /**
     * AgendaItemController constructor.
     */
    public function __construct(RepositorieFactory $repositorieFactory)
    {
        $this->_agendaItemRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$AGENDAITEMREPOKEY);
        $this->_agendaItemCategoryRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$AGENDAITEMRECATEGORYPOKEY);
        $this->_applicationFormRepostory = $repositorieFactory->getRepositorie(RepositorieFactory::$APPLICATIONFORMREPOKEY);

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
        $agendaItems = $this->_agendaItemRepository->all(array('title','startdate','endDate','id','application_form_id'));

        return view('beheer.agendaItem.index',compact('agendaItems'));
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

        foreach ($this->_applicationFormRepostory->all(array('id','name')) as $form){
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
            Image::make("../storage/app/public/" . $agendaItem->image_url,array(
                'width' => 400,
                'height' => 200
            ))->save("../storage/app/public/" . $agendaItem->image_url);
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

        foreach ($this->_applicationFormRepostory->all(array('id','name')) as $form){
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
            Image::make("../storage/app/public/" . $agendaItem->image_url,array(
                'width' => 400,
                'height' => 200
            ))->save("../storage/app/public/" . $agendaItem->image_url);
        }

        \Session::flash("message",trans('AgendaItems.added'));
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

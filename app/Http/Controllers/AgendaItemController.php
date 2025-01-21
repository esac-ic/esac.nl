<?php

namespace App\Http\Controllers;

use App\AgendaItem;
use App\Repositories\AgendaItemCategoryRepository;
use App\Repositories\AgendaItemRepository;
use App\Repositories\ApplicationFormRepositories\ApplicationFormRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AgendaItemController extends Controller
{
    private $_agendaItemRepository;
    private $_agendaItemCategoryRepository;
    private $_applicationFormRepository;
    private $_imageManager;

    public function __construct(
        AgendaItemRepository $agendaItemRepository,
        AgendaItemCategoryRepository $agendaItemCategoryRepository,
        ApplicationFormRepository $applicationFormRepository
    ) {
        $this->middleware('auth');
        $this->middleware('authorize:' . config('constants.Content_administrator') . ',' . config('constants.Activity_administrator'));

        $this->_agendaItemRepository = $agendaItemRepository;
        $this->_agendaItemCategoryRepository = $agendaItemCategoryRepository;
        $this->_applicationFormRepository = $applicationFormRepository;
        $this->_imageManager = new ImageManager(new Driver());
    }

    public function index()
    {
        return view('beheer.agendaItem.index');
    }

    public function create()
    {
        $fields = $this->getFields('POST', '/agendaItems', 'Add an event');
        return $this->renderCreateEditView(null, $fields);
    }

    public function store(Request $request)
    {
        $this->validateInput($request);
        $agendaItem = $this->_agendaItemRepository->create($request->all());
        $this->handleImage($request, $agendaItem);
        Session::flash("message", 'Event added');
        return redirect('/agendaItems');
    }

    public function show(AgendaItem $agendaItem)
    {
        return view('beheer.agendaItem.show', compact('agendaItem'));
    }

    public function edit(AgendaItem $agendaItem)
    {
        $fields = $this->getFields('PATCH', '/agendaItems/' . $agendaItem->id, 'Edit event');
        return $this->renderCreateEditView($agendaItem, $fields);
    }

    public function update(Request $request, AgendaItem $agendaItem)
    {
        $this->validateInput($request);
        $this->_agendaItemRepository->update($agendaItem->id, $request->all());
        $this->handleImage($request, $agendaItem);
        Session::flash("message", 'Event edited');
        return redirect('/agendaItems');
    }

    public function destroy($id)
    {
        $this->_agendaItemRepository->delete($id);
        Session::flash("message", 'Event removed');
        return redirect('/agendaItems');
    }

    public function copy(AgendaItem $agendaItem)
    {
        $newAgendaItem = $this->_agendaItemRepository->copy($agendaItem);
        return redirect('/agendaItems/' . $newAgendaItem->id . '/edit');
    }

    private function validateInput(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'shortDescription' => 'required|max:100',
            'text' => 'required',
            'thumbnail' => 'image|mimes:jpeg,png,jpg',
            'category' => 'required|numeric|min:1',
            'applicationForm' => 'required|numeric|min:0',
            'endDate' => 'required|date',
            'startDate' => 'required|date',
        ]);
    }

    private function getFields($method, $url, $title)
    {
        return [
            'title_info' => 'Event general information',
            'title_content' => 'Event item content',
            'title_image' => 'Event item thumbnail',
            'title' => $title,
            'method' => $method,
            'url' => $url,
        ];
    }

    private function renderCreateEditView($agendaItem, $fields)
    {
        $agendaItemCategories = $this->getCategories();
        $applicationForms = $this->getForms();
        return view('beheer.agendaItem.create_edit', compact(['fields', 'agendaItem', 'agendaItemCategories', 'applicationForms']));
    }

    private function getCategories()
    {
        $categories = [];
        foreach ($this->_agendaItemCategoryRepository->all(['id', 'name']) as $category) {
            $categories[$category->id] = $category->name;
        }
        return $categories;
    }

    private function getForms()
    {
        $forms = [];
        foreach ($this->_applicationFormRepository->all(['id', 'name']) as $form) {
            $forms[$form->id] = $form->name;
        }
        return $forms;
    }

    private function handleImage(Request $request, AgendaItem $agendaItem)
    {
        if ($request->hasFile('thumbnail')) {
            $name = $agendaItem->id . '-thumbnail.' . $request->thumbnail->extension();
            $request->file('thumbnail')->storeAs('agendaItem', $name, 'public');
            $agendaItem->image_url = 'agendaItem/' . $name;
            $agendaItem->save();
            
            $img_path = "../storage/app/public/" . $agendaItem->image_url;
            $image = $this->_imageManager->read($img_path);
            $image->cover(400, 300);
            $image->save();
        }
    }
}

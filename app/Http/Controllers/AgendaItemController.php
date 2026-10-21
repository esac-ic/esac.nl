<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\AgendaItem;
use App\Repositories\AgendaItemCategoryRepository;
use App\Repositories\AgendaItemRepository;
use App\Repositories\ApplicationFormRepositories\ApplicationFormRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class AgendaItemController extends Controller
{
    public function __construct(
        private readonly AgendaItemRepository $_agendaItemRepository,
        private readonly AgendaItemCategoryRepository $_agendaItemCategoryRepository,
        private readonly ApplicationFormRepository $_applicationFormRepository
    ) {
        $this->middleware('auth');
        $this->middleware('authorize:' . Role::ContentAdministrator->value . ',' . Role::ActivityAdministrator->value);
    }

    public function index(): View
    {
        return view('beheer.agendaItem.index');
    }

    public function create()
    {
        $fields = $this->getFields('POST', '/agendaItems', 'Add an event');
        return $this->renderCreateEditView(null, $fields);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validateInput($request);
        $agendaItem = $this->_agendaItemRepository->create($request->all());
        $this->handleImage($request, $agendaItem);
        Session::flash("message", 'Event added');
        return redirect('/agendaItems');
    }

    public function show(AgendaItem $agendaItem): View
    {
        return view('beheer.agendaItem.show', compact('agendaItem'));
    }

    public function edit(AgendaItem $agendaItem)
    {
        $fields = $this->getFields('PATCH', '/agendaItems/' . $agendaItem->id, 'Edit event');
        return $this->renderCreateEditView($agendaItem, $fields);
    }

    public function update(Request $request, AgendaItem $agendaItem): RedirectResponse
    {
        $this->validateInput($request);
        $this->_agendaItemRepository->update($agendaItem->id, $request->all());
        $this->handleImage($request, $agendaItem);
        Session::flash("message", 'Event edited');
        return redirect('/agendaItems');
    }

    public function destroy($id): RedirectResponse
    {
        $this->_agendaItemRepository->delete($id);
        Session::flash("message", 'Event removed');
        return redirect('/agendaItems');
    }

    public function copy(AgendaItem $agendaItem): RedirectResponse
    {
        $newAgendaItem = $this->_agendaItemRepository->copy($agendaItem);
        return redirect('/agendaItems/' . $newAgendaItem->id . '/edit');
    }

    private function validateInput(Request $request): void
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

    private function getFields(string $method, string $url, string $title): array
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

    private function renderCreateEditView(?AgendaItem $agendaItem, $fields): View
    {
        $agendaItemCategories = $this->getCategories();
        $applicationForms = $this->getForms();
        return view('beheer.agendaItem.create_edit', compact(['fields', 'agendaItem', 'agendaItemCategories', 'applicationForms']));
    }

    /**
     * @return mixed[]
     */
    private function getCategories(): array
    {
        $categories = [];
        foreach ($this->_agendaItemCategoryRepository->all(['id', 'name']) as $category) {
            $categories[$category->id] = $category->name;
        }
        return $categories;
    }

    /**
     * @return mixed[]
     */
    private function getForms(): array
    {
        $forms = [];
        foreach ($this->_applicationFormRepository->all(['id', 'name']) as $form) {
            $forms[$form->id] = $form->name;
        }
        return $forms;
    }

    private function handleImage(Request $request, AgendaItem $agendaItem): void
    {
        if ($request->hasFile('thumbnail')) {
            $name = $agendaItem->id . '-thumbnail.' . $request->thumbnail->extension();
            $request->file('thumbnail')->storeAs('agendaItem', $name, 'public');
            $agendaItem->image_url = 'agendaItem/' . $name;
            $agendaItem->save();
            $img_path = Storage::disk('public')->path($agendaItem->image_url);
            ImageManager::usingDriver(Driver::class)->decodePath($img_path)->cover(400, 300)->save($img_path);
        }
    }
}

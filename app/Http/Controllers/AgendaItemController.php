<?php

namespace App\Http\Controllers;

use App\Models\AgendaItem;
use App\Models\AgendaItemCategory;
use App\Models\ApplicationForm\ApplicationForm;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class AgendaItemController extends Controller implements HasMiddleware
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
        return view('beheer.agendaItem.index');
    }

    public function create()
    {
        $fields = $this->getFields('POST', route('agendaItems.store'), 'Add an event');
        return $this->renderCreateEditView(null, $fields);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validateInput($request);
        $agendaItem = new AgendaItem($request->all())->fillFromForm($request->all());
        $agendaItem->image_url = "";
        $agendaItem->created_by = $request->user()->id;
        $agendaItem->save();
        $this->handleImage($request, $agendaItem);
        Session::flash("message", 'Event added');
        return to_route('agendaItems.index');
    }

    public function show(AgendaItem $agendaItem): View
    {
        return view('beheer.agendaItem.show', compact('agendaItem'));
    }

    public function edit(AgendaItem $agendaItem)
    {
        $fields = $this->getFields('PATCH', route('agendaItems.update', $agendaItem), 'Edit event');
        return $this->renderCreateEditView($agendaItem, $fields);
    }

    public function update(Request $request, AgendaItem $agendaItem): RedirectResponse
    {
        $this->validateInput($request);
        $agendaItem->title = $request->input('title');
        $agendaItem->text = $request->input('text');
        $agendaItem->short_description = $request->input('short_description');
        $agendaItem->category = $request->input('category');
        $agendaItem->fillFromForm($request->all())->save();
        $this->handleImage($request, $agendaItem);
        Session::flash("message", 'Event edited');
        return to_route('agendaItems.index');
    }

    public function destroy(AgendaItem $agendaItem): RedirectResponse
    {
        $agendaItem->delete();

        if ($agendaItem->image_url != "") {
            Storage::disk('public')->delete($agendaItem->image_url);
        }

        Session::flash("message", 'Event removed');
        return to_route('agendaItems.index');
    }

    public function copy(Request $request, AgendaItem $agendaItem): RedirectResponse
    {
        $newAgendaItem = $agendaItem->copyFor($request->user());
        return to_route('agendaItems.edit', $newAgendaItem);
    }

    private function validateInput(Request $request): void
    {
        $request->validate([
            'title' => 'required|max:255',
            'short_description' => 'required|max:100',
            'text' => 'required',
            'thumbnail' => 'image|mimes:jpeg,png,jpg',
            'category' => 'required|numeric|min:1',
            'applicationForm' => 'required|numeric|min:0',
            'end_date' => 'required|date',
            'start_date' => 'required|date',
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
        foreach (AgendaItemCategory::all(['id', 'name']) as $category) {
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
        foreach (ApplicationForm::all(['id', 'name']) as $form) {
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

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaItemRequest;
use App\Models\AgendaItem;
use App\Models\AgendaItemCategory;
use App\Models\ApplicationForm\ApplicationForm;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
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

    public function create(): View
    {
        return $this->form(new AgendaItem);
    }

    public function store(AgendaItemRequest $request): RedirectResponse
    {
        $agendaItem = new AgendaItem($request->validated())->fillFromForm($request->validated());
        $agendaItem->image_url = '';
        $agendaItem->created_by = $request->user()->id;
        $agendaItem->save();
        $this->handleImage($request, $agendaItem);

        return to_route('beheer.agenda-items.index')->with('message', 'Event added');
    }

    public function show(AgendaItem $agendaItem): View
    {
        return view('beheer.agendaItem.show', ['agendaItem' => $agendaItem]);
    }

    public function edit(AgendaItem $agendaItem): View
    {
        return $this->form($agendaItem);
    }

    public function update(AgendaItemRequest $request, AgendaItem $agendaItem): RedirectResponse
    {
        $agendaItem->fill($request->safe()->only(['title', 'text', 'short_description', 'category', 'start_date', 'end_date']))
            ->fillFromForm($request->validated())
            ->save();
        $this->handleImage($request, $agendaItem);

        return to_route('beheer.agenda-items.index')->with('message', 'Event edited');
    }

    public function destroy(AgendaItem $agendaItem): RedirectResponse
    {
        $agendaItem->delete();

        if ($agendaItem->image_url != '') {
            Storage::disk('public')->delete($agendaItem->image_url);
        }

        return to_route('beheer.agenda-items.index')->with('message', 'Event removed');
    }

    public function copy(Request $request, AgendaItem $agendaItem): RedirectResponse
    {
        $newAgendaItem = $agendaItem->copyFor($request->user());

        return to_route('beheer.agenda-items.edit', $newAgendaItem);
    }

    private function form(AgendaItem $agendaItem): View
    {
        return view('beheer.agendaItem.create_edit', [
            'agendaItem' => $agendaItem,
            'agendaItemCategories' => AgendaItemCategory::query()->orderBy('name')->pluck('name', 'id')->all(),
            'applicationForms' => ApplicationForm::query()->orderBy('name')->pluck('name', 'id')->all(),
        ]);
    }

    private function handleImage(Request $request, AgendaItem $agendaItem): void
    {
        if ($request->hasFile('thumbnail')) {
            $name = $agendaItem->id.'-thumbnail.'.$request->thumbnail->extension();
            $request->file('thumbnail')->storeAs('agendaItem', $name, 'public');
            $agendaItem->image_url = 'agendaItem/'.$name;
            $agendaItem->save();
            $img_path = Storage::disk('public')->path($agendaItem->image_url);
            ImageManager::usingDriver(Driver::class, decodeAnimation: false)->decodePath($img_path)->cover(400, 300)->save($img_path);
        }
    }
}

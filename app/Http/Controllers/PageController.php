<?php

namespace App\Http\Controllers;

use App\Http\Requests\PageRequest;
use App\Models\MenuItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-pages'),
        ];
    }

    public function index(): View
    {
        return view('admin.pages.index', [
            'pages' => MenuItem::query()->with('parent')->orderBy('parent_id')->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        // A new page goes last in its menu, unless another position is chosen.
        return $this->form(new MenuItem(['position' => MenuItem::query()->max('position') + 10]));
    }

    public function store(PageRequest $request): RedirectResponse
    {
        $menuItem = new MenuItem($request->safe()->only(['name', 'url_name', 'content']));
        $this->fillMenuPosition($request, $menuItem);
        // These can only be changed in the database.
        $menuItem->deletable = true;
        $menuItem->editable = true;
        $menuItem->save();

        return to_route('admin.pages.index')->with('message', 'Page is added');
    }

    public function show(MenuItem $page): View
    {
        return view('admin.pages.show', [
            'page' => $page,
            'subItems' => MenuItem::query()->inMenu()->where('parent_id', $page->id)->ordered()->get(),
        ]);
    }

    public function edit(MenuItem $page): View
    {
        return $this->form($page);
    }

    public function update(PageRequest $request, MenuItem $page): RedirectResponse
    {
        // The request excludes the URL name and content of pages that aren't editable.
        $page->fill($request->safe()->only(['name', 'url_name', 'content']));
        $this->fillMenuPosition($request, $page);
        $page->save();

        return to_route('admin.pages.index')->with('message', 'Page is edited');
    }

    public function destroy(MenuItem $page): RedirectResponse
    {
        if ($page->deletable) {
            // Remove the references from the other menu items.
            MenuItem::query()->where('parent_id', $page->id)->update(['parent_id' => null]);

            $page->delete();
        }

        return to_route('admin.pages.index')->with('message', 'Menu item removed');
    }

    private function fillMenuPosition(PageRequest $request, MenuItem $menuItem): void
    {
        $menuItem->parent_id = $request->validated('itemType') === 'subItem' ? $request->validated('parentItem') : null;
        $menuItem->position = $request->validated('position');
        $menuItem->login = $request->validated('login');
        $menuItem->menu_item = $request->validated('menu_item');
    }

    private function form(MenuItem $page): View
    {
        return view('admin.pages.create_edit', [
            'page' => $page,
            'pages' => MenuItem::query()->orderBy('name')->get(),
        ]);
    }
}

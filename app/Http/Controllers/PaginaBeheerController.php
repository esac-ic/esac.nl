<?php

namespace App\Http\Controllers;

use App\Http\Requests\PageRequest;
use App\Models\MenuItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PaginaBeheerController extends Controller implements HasMiddleware
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
        return view('beheer.menu.index', [
            'pages' => MenuItem::all(['name', 'id', 'after', 'parent_id']),
        ]);
    }

    public function create(): View
    {
        return view('beheer.menu.create_edit', [
            'page' => null,
            'fields' => [
                'title_menu' => 'Add menu item ',
                'title_page' => 'Add page',
                'method' => 'POST',
                'url' => route('pages.store'),
            ],
            'pages' => MenuItem::query()->orderBy('name')->get(),
        ]);
    }

    public function store(PageRequest $request): RedirectResponse
    {
        $menuItem = new MenuItem($request->safe()->only(['name', 'url_name', 'content']));
        $this->fillMenuPosition($request, $menuItem);
        // These can only be changed in the database.
        $menuItem->deletable = true;
        $menuItem->editable = true;
        $menuItem->save();

        return to_route('pages.index')->with('message', 'Page is added');
    }

    public function show(MenuItem $page): View
    {
        return view('beheer.menu.show', [
            'page' => $page,
            'subItems' => MenuItem::inMenu()->where('parent_id', $page->id)->get(),
        ]);
    }

    public function edit(MenuItem $page): View
    {
        return view('beheer.menu.create_edit', [
            'page' => $page,
            'fields' => [
                'title_menu' => 'Edit menu page',
                'title_page' => 'Edit page',
                'method' => 'PATCH',
                'url' => route('pages.update', $page),
            ],
            'pages' => MenuItem::query()->orderBy('name')->get(),
        ]);
    }

    public function update(PageRequest $request, MenuItem $page): RedirectResponse
    {
        // The request excludes the URL name and content of pages that aren't editable.
        $page->fill($request->safe()->only(['name', 'url_name', 'content']));
        $this->fillMenuPosition($request, $page);
        $page->save();

        return to_route('pages.index')->with('message', 'Page is edited');
    }

    public function destroy(MenuItem $page): RedirectResponse
    {
        if ($page->deletable) {
            // Remove the references from the other menu items.
            MenuItem::where('parent_id', $page->id)->update(['parent_id' => null]);
            MenuItem::where('after', $page->id)->update(['after' => null]);

            $page->delete();
        }

        return to_route('pages.index')->with('message', 'Menu item removed');
    }

    private function fillMenuPosition(PageRequest $request, MenuItem $menuItem): void
    {
        $menuItem->parent_id = $request->validated('itemType') === 'subItem' ? $request->validated('parentItem') : null;
        $menuItem->after = $request->validated('afterItem');
        $menuItem->login = $request->has('login');
        $menuItem->menu_item = $request->has('menu_item');
    }
}

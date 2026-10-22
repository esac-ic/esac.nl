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
        $data = $request->all();
        $menuItem = new MenuItem($data);
        if ($data['itemType'] === 'subItem') {
            $menuItem->parent_id = $data['parentItem'];
        }
        $menuItem->url_name = $data['url_name'];
        $menuItem->after = $data['afterItem'] == -1 ? null : $data['afterItem'];
        $menuItem->login = array_key_exists('login', $data);
        $menuItem->menu_item = array_key_exists('menu_item', $data);
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
        $data = $request->all();
        $page->update($data);

        $page->parent_id = $data['itemType'] === 'subItem' ? $data['parentItem'] : null;
        if ($page->editable) {
            $page->url_name = $data['url_name'];
        }
        $page->menu_item = array_key_exists('menu_item', $data);
        $page->after = $data['afterItem'] == -1 ? null : $data['afterItem'];
        $page->login = array_key_exists('login', $data);
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
}

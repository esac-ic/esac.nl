<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Session;

class PaginaBeheerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-pages'),
        ];
    }

    //gives the rol views
    public function index(): View
    {
        $pages = MenuItem::all(["name", "id", "after", "parent_id"]);
        return view('beheer.menu.index', compact('pages'));
    }

    //show create screen
    public function create(): View
    {
        $fields = [
            'title_menu' => 'Add menu item ',
            'title_page' => 'Add page',
            'method' => 'POST',
            'url' => route('pages.store')];
        $page = null;
        $pages = MenuItem::all();
        return view('beheer.menu.create_edit', compact(['page', 'fields', 'pages']));
    }

    //store rol
    public function store(Request $request): RedirectResponse
    {
        $this->validateData($request);

        $data = $request->all();
        $menuItem = new MenuItem($data);
        if ($data['itemType'] === "subItem") {
            $menuItem->parent_id = $data['parentItem'];
        }
        $menuItem->url_name = $data['url_name'];
        $menuItem->after = $data['afterItem'] == -1 ? null : $data['afterItem'];
        $menuItem->login = array_key_exists("login", $data);
        $menuItem->menu_item = array_key_exists("menu_item", $data);
        //can only be set in the database
        $menuItem->deletable = true;
        $menuItem->editable = true;
        $menuItem->save();

        Session::flash("message", 'Page is added');
        return to_route('pages.index');
    }

    public function show(Request $request, MenuItem $page): View
    {
        $subItems = MenuItem::inMenu()->where('parent_id', $page->id)->get();
        return view('beheer.menu.show', compact('page', 'subItems'));
    }

    //show edit screen
    public function edit(Request $request, MenuItem $page): View
    {
        $fields = [
            'title_menu' => 'Edit menu page',
            'title_page' => 'Edit page',
            'method' => 'PATCH',
            'url' => route('pages.update', $page)];
        $pages = MenuItem::all();
        return view('beheer.menu.create_edit', compact(['page', 'fields', 'pages']));
    }

    //update page
    public function update(Request $request, MenuItem $page): RedirectResponse
    {
        if ($page->url_name != $request->url_name && $page->editable) {
            $this->validateData($request);
        }

        $data = $request->all();
        $page->update($data);

        $page->parent_id = $data['itemType'] === "subItem" ? $data['parentItem'] : null;
        if ($page->editable) {
            $page->url_name = $data['url_name'];
        }
        $page->menu_item = array_key_exists("menu_item", $data);
        $page->after = $data['afterItem'] == -1 ? null : $data['afterItem'];
        $page->login = array_key_exists("login", $data);
        $page->save();

        Session::flash("message", 'Page is edited');
        return to_route('pages.index');
    }

    public function destroy(Request $request, MenuItem $page): RedirectResponse
    {
        if ($page->deletable) {
            //delete references from the other menu items
            MenuItem::where('parent_id', $page->id)->update(['parent_id' => null]);
            MenuItem::where('after', $page->id)->update(['after' => null]);

            $page->delete();
        }
        Session::flash("message", 'Menu item removed');

        return to_route('pages.index');
    }

    private function validateData(Request $request): void
    {
        $request->validate([
            'url_name' => 'required|max:255|unique:menu_items',
            'itemType' => 'required',
            'name' => 'required|max:255',
            'content' => 'required',
            'afterItem' => 'required',
        ]);

        if ($request->input('itemType') === 'subItem') {
            $request->validate([
                'parentItem' => 'required',
            ]);
        }
    }
}

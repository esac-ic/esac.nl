<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Repositories\MenuRepository;
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private readonly MenuRepository $_menuRepository)
    {
    }

    //gives the rol views
    public function index(): View
    {
        $pages = $this->_menuRepository->all(["name", "id", "after", "parent_id"]);
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
        $pages = $this->_menuRepository->all();
        return view('beheer.menu.create_edit', compact(['page', 'fields', 'pages']));
    }

    //store rol
    public function store(Request $request): RedirectResponse
    {
        $this->validateData($request);

        $this->_menuRepository->create($request->all());

        Session::flash("message", 'Page is added');
        return to_route('pages.index');
    }

    public function show(Request $request, MenuItem $page): View
    {
        $subItems = $page->getSubMenuItems($page->id);
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
        $pages = $this->_menuRepository->all();
        return view('beheer.menu.create_edit', compact(['page', 'fields', 'pages']));
    }

    //update page
    public function update(Request $request, MenuItem $page): RedirectResponse
    {
        if ($page->url_name != $request->url_name && $page->editable) {
            $this->validateData($request);
        }

        $this->_menuRepository->update($page->id, $request->all());

        Session::flash("message", 'Page is edited');
        return to_route('pages.index');
    }

    public function destroy(Request $request, MenuItem $page): RedirectResponse
    {
        $this->_menuRepository->delete($page->id);
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

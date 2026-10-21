<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\MenuItem;
use App\Repositories\MenuRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PaginaBeheerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private readonly MenuRepository $_menuRepository)
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Role::ContentAdministrator->value);
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
            'url' => '/pages'];
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
        return redirect('/pages');
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
            'url' => '/pages/' . $page->id];
        $pages = $this->_menuRepository->all();
        return view('beheer.menu.create_edit', compact(['page', 'fields', 'pages']));
    }

    //update page
    public function update(Request $request, MenuItem $page): RedirectResponse
    {
        if ($page->urlName != $request->urlName && $page->editable) {
            $this->validateData($request);
        }

        $this->_menuRepository->update($page->id, $request->all());

        Session::flash("message", 'Page is edited');
        return redirect('/pages');
    }

    public function destroy(Request $request, MenuItem $page): RedirectResponse
    {
        $this->_menuRepository->delete($page->id);
        Session::flash("message", 'Menu item removed');

        return redirect('/pages');
    }

    private function validateData(Request $request): void
    {
        $this->validate($request, [
            'urlName' => 'required|max:255|unique:menu_items',
            'itemType' => 'required',
            'name' => 'required|max:255',
            'content' => 'required',
            'afterItem' => 'required',
        ]);

        if ($request->input('itemType') === 'subItem') {
            $this->validate($request, [
                'parentItem' => 'required',
            ]);
        }
    }
}

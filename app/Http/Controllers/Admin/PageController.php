<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Models\MenuItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class PageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage-pages'),
        ];
    }

    public function index(): View
    {
        $pages = MenuItem::query()->whereNull('parent_id')->with(['children' => fn ($query) => $query->ordered()])->ordered()->get();

        // Each top-level page is followed by its sub pages, in menu order.
        return view('admin.pages.index', [
            'pages' => $pages->flatMap(fn (MenuItem $page) => [$page, ...$page->children]),
        ]);
    }

    public function create(): View
    {
        return $this->form(new MenuItem);
    }

    public function store(PageRequest $request): RedirectResponse
    {
        $menuItem = new MenuItem($request->safe()->only(['name', 'slug', 'content']));
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
            'children' => $page->children()->inMenu()->ordered()->get(),
        ]);
    }

    public function edit(MenuItem $page): View
    {
        return $this->form($page);
    }

    public function update(PageRequest $request, MenuItem $page): RedirectResponse
    {
        // The request excludes a fixed slug, and the content of pages that aren't editable.
        $page->fill($request->safe()->only(['name', 'slug', 'content']));
        $this->fillMenuPosition($request, $page);
        $page->save();

        return to_route('admin.pages.index')->with('message', 'Page is edited');
    }

    public function destroy(MenuItem $page): RedirectResponse
    {
        if ($page->deletable) {
            // Remove the references from the other menu items.
            $page->children()->update(['parent_id' => null]);

            $page->delete();
        }

        return to_route('admin.pages.index')->with('message', 'Menu item removed');
    }

    public function move(Request $request, MenuItem $page): RedirectResponse
    {
        $page->move($request->validate(['direction' => ['required', Rule::in(['up', 'down'])]])['direction']);

        return to_route('admin.pages.index');
    }

    private function fillMenuPosition(PageRequest $request, MenuItem $menuItem): void
    {
        $menuItem->parent_id = $request->validated('parent_id');
        // A new page, or one that moves to another menu, goes last.
        if (! $menuItem->exists || $menuItem->isDirty('parent_id')) {
            $menuItem->position = MenuItem::query()->where('parent_id', $menuItem->parent_id)->max('position') + 1;
        }
        $menuItem->requires_login = $request->validated('requires_login');
        $menuItem->show_in_menu = $request->validated('show_in_menu');
    }

    private function form(MenuItem $page): View
    {
        return view('admin.pages.form', [
            'page' => $page,
            // A page with sub pages stays top-level.
            'parents' => $page->children()->exists() ? null : MenuItem::query()->whereNull('parent_id')->whereKeyNot($page->id)->orderBy('name')->get(),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Models\Page;
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
        $pages = Page::query()->topLevel()->with(['children' => fn ($query) => $query->ordered()])->ordered()->get();

        // Each top-level page is followed by its sub pages, in menu order.
        return view('admin.pages.index', [
            'pages' => $pages->flatMap(fn (Page $page) => [$page, ...$page->children]),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Page);
    }

    public function store(PageRequest $request): RedirectResponse
    {
        $page = new Page($request->safe()->only(['name', 'slug', 'content', 'requires_login']));
        $this->fillMenuPosition($request, $page);
        // These can only be changed in the database.
        $page->deletable = true;
        $page->editable = true;
        $page->save();

        return to_route('admin.pages.index')->with('message', 'Page is added');
    }

    public function show(Page $page): View
    {
        return view('admin.pages.show', [
            'page' => $page,
            'children' => $page->children()->inMenu()->ordered()->get(),
        ]);
    }

    public function edit(Page $page): View
    {
        return $this->form($page);
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        // The request excludes a fixed slug and its login setting, and the content of pages that aren't editable.
        $page->fill($request->safe()->only(['name', 'slug', 'content', 'requires_login']));
        $this->fillMenuPosition($request, $page);
        $page->save();

        return to_route('admin.pages.index')->with('message', 'Page is edited');
    }

    public function destroy(Page $page): RedirectResponse
    {
        abort_unless($page->deletable, 403);

        // The sub pages become top-level pages.
        $page->children()->update(['parent_id' => null]);
        $page->delete();

        return to_route('admin.pages.index')->with('message', 'Page is removed');
    }

    public function move(Request $request, Page $page): RedirectResponse
    {
        $page->move($request->validate(['direction' => ['required', Rule::in(['up', 'down'])]])['direction']);

        return to_route('admin.pages.index');
    }

    private function fillMenuPosition(PageRequest $request, Page $page): void
    {
        $page->parent_id = $request->validated('parent_id');
        // A new page, or one that moves to another menu, goes last.
        if (! $page->exists || $page->isDirty('parent_id')) {
            $page->position = Page::query()->where('parent_id', $page->parent_id)->max('position') + 1;
        }
        $page->show_in_menu = $request->validated('show_in_menu');
    }

    private function form(Page $page): View
    {
        return view('admin.pages.form', [
            'page' => $page,
            // A page with sub pages stays top-level.
            'parents' => $page->children()->exists() ? null : Page::query()->topLevel()->whereKeyNot($page->id)->orderBy('name')->get(),
        ]);
    }
}

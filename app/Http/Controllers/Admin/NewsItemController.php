<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsItemRequest;
use App\Models\NewsItem;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class NewsItemController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage-news'),
        ];
    }

    public function index(): View
    {
        return view('admin.news-items.index', [
            'newsItems' => NewsItem::all(),
        ]);
    }

    public function create(): View
    {
        return $this->form(new NewsItem);
    }

    public function store(NewsItemRequest $request): RedirectResponse
    {
        $newsItem = NewsItem::query()->create($request->safe()->except('image'));

        if ($request->hasFile('image')) {
            $this->storeImages($request, $newsItem);
        }

        return to_route('admin.news-items.index')->with('message', 'News item added');
    }

    public function show(NewsItem $newsItem): View
    {
        return view('admin.news-items.show', ['newsItem' => $newsItem]);
    }

    public function edit(NewsItem $newsItem): View
    {
        return $this->form($newsItem);
    }

    public function update(NewsItemRequest $request, NewsItem $newsItem): RedirectResponse
    {
        $newsItem->update($request->safe()->except('image'));

        if ($request->hasFile('image')) {
            $this->storeImages($request, $newsItem);
        }

        return to_route('admin.news-items.index')->with('message', 'News item edited');
    }

    public function destroy(NewsItem $newsItem): RedirectResponse
    {
        $newsItem->delete();

        return to_route('admin.news-items.index')->with('message', 'News item removed');
    }

    private function storeImages(Request $request, NewsItem $newsItem): void
    {
        $newsItem->addMediaFromRequest('image')->toMediaCollection('image');
    }

    private function form(NewsItem $newsItem): View
    {
        return view('admin.news-items.form', [
            'newsItem' => $newsItem,
            'memberNames' => User::query()->active()->orderBy('first_name')->get()->pluck('name'),
        ]);
    }
}

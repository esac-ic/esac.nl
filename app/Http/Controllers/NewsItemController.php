<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsItemRequest;
use App\Models\NewsItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class NewsItemController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-news'),
        ];
    }

    public function index(): View
    {
        return view('admin.news-items.index', [
            'newsItems' => NewsItem::all(['id', 'title', 'created_at', 'author']),
        ]);
    }

    public function create(): View
    {
        return view('admin.news-items.create_edit', ['newsItem' => new NewsItem]);
    }

    public function store(NewsItemRequest $request): RedirectResponse
    {
        $newsItem = NewsItem::query()->create($request->validated());

        if ($request->hasFile('thumbnail')) {
            $this->uploadAndResizeImage($request, $newsItem);
        }

        return to_route('admin.news-items.index')->with('message', 'News item added');
    }

    public function show(NewsItem $newsItem): View
    {
        return view('admin.news-items.show', ['newsItem' => $newsItem]);
    }

    public function edit(NewsItem $newsItem): View
    {
        return view('admin.news-items.create_edit', ['newsItem' => $newsItem]);
    }

    public function update(NewsItemRequest $request, NewsItem $newsItem): RedirectResponse
    {
        $newsItem->update($request->validated());

        if ($request->hasFile('thumbnail')) {
            $this->uploadAndResizeImage($request, $newsItem);
        }

        return to_route('admin.news-items.index')->with('message', 'News item edited');
    }

    public function destroy(NewsItem $newsItem): RedirectResponse
    {
        $newsItem->delete();

        return to_route('admin.news-items.index')->with('message', 'News item removed');
    }

    private function uploadAndResizeImage(Request $request, NewsItem $newsItem): void
    {
        $headerFileName = $newsItem->id.'-header.'.$request->thumbnail->extension();
        $thumbnailFileName = $newsItem->id.'-thumbnail.'.$request->thumbnail->extension();

        $request->file('thumbnail')->storeAs('newsItems', $headerFileName, 'public');
        $newsItem->image_url = 'newsItems/'.$headerFileName;

        $request->file('thumbnail')->storeAs('newsItems', $thumbnailFileName, 'public');
        $newsItem->thumbnail_url = 'newsItems/'.$thumbnailFileName;
        $newsItem->save();

        // resize both images
        $this->resizeImage($newsItem->image_url, 1200, 500);
        $this->resizeImage($newsItem->thumbnail_url, 400, 300);
    }

    private function resizeImage(string $path, int $width, int $height): void
    {
        $imagePath = Storage::disk('public')->path($path);
        ImageManager::usingDriver(Driver::class, decodeAnimation: false)->decodePath($imagePath)->cover($width, $height)->save($imagePath);
    }
}

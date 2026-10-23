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
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
            'newsItems' => NewsItem::all(['id', 'title', 'created_at', 'author']),
        ]);
    }

    public function create(): View
    {
        return $this->form(new NewsItem);
    }

    public function store(NewsItemRequest $request): RedirectResponse
    {
        $newsItem = NewsItem::query()->create($request->validated());

        if ($request->hasFile('thumbnail')) {
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
        $newsItem->update($request->validated());

        if ($request->hasFile('thumbnail')) {
            $this->storeImages($request, $newsItem);
        }

        return to_route('admin.news-items.index')->with('message', 'News item edited');
    }

    public function destroy(NewsItem $newsItem): RedirectResponse
    {
        $newsItem->delete();

        return to_route('admin.news-items.index')->with('message', 'News item removed');
    }

    /**
     * Stores the uploaded image as a 1200x500 header and a 400x300 thumbnail.
     */
    private function storeImages(Request $request, NewsItem $newsItem): void
    {
        $headerFileName = $newsItem->id.'-header.'.$request->thumbnail->extension();
        $thumbnailFileName = $newsItem->id.'-thumbnail.'.$request->thumbnail->extension();

        $request->file('thumbnail')->storeAs('newsItems', $headerFileName, 'public');
        $newsItem->image_path = 'newsItems/'.$headerFileName;

        $request->file('thumbnail')->storeAs('newsItems', $thumbnailFileName, 'public');
        $newsItem->thumbnail_path = 'newsItems/'.$thumbnailFileName;
        $newsItem->save();

        $this->resizeImage($newsItem->image_path, 1200, 500);
        $this->resizeImage($newsItem->thumbnail_path, 400, 300);
    }

    private function resizeImage(string $path, int $width, int $height): void
    {
        $imagePath = Storage::disk('public')->path($path);
        ImageManager::usingDriver(Driver::class, decodeAnimation: false)->decodePath($imagePath)->cover($width, $height)->save($imagePath);
    }

    private function form(NewsItem $newsItem): View
    {
        return view('admin.news-items.form', [
            'newsItem' => $newsItem,
            'memberNames' => User::query()->active()->orderBy('first_name')->get()->pluck('name'),
        ]);
    }
}

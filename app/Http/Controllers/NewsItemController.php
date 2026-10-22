<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\NewsItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class NewsItemController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('authorize:' . Role::ContentAdministrator->value . ',' . Role::ActivityAdministrator->value),
        ];
    }

    public function index(): View
    {
        $newsItems = NewsItem::all(['id', 'title', 'created_at', 'author']);
        return view('beheer.newsItem.index', compact('newsItems'));
    }

    public function create(): View
    {
        $fields = ['title' => 'Add a news item ', 'method' => 'POST', 'url' => route('newsItems.store')];
        $newsItem = null;
        return view('beheer.newsItem.create_edit', compact('fields', 'newsItem'));
    }

    public function store(Request $request): RedirectResponse
    {
        $newsItem = NewsItem::create($this->validateInput($request));

        if ($request->hasFile('thumbnail')) {
            $this->uploadAndResizeImage($request, $newsItem);
        }

        Session::flash("message", 'News item added');
        return to_route('newsItems.index');
    }

    public function show(Request $request, NewsItem $newsItem): View
    {
        return view('beheer.newsItem.show', compact('newsItem'));
    }

    public function edit(Request $request, NewsItem $newsItem): View
    {
        $fields = ['title' => 'Edit news item ', 'method' => 'PATCH', 'url' => route('newsItems.update', $newsItem)];
        return view('beheer.newsItem.create_edit', compact('fields', 'newsItem'));
    }

    public function update(Request $request, NewsItem $newsItem): RedirectResponse
    {
        $newsItem->update($this->validateInput($request));

        if ($request->hasFile('thumbnail')) {
            $this->uploadAndResizeImage($request, $newsItem);
        }

        Session::flash("message", 'News item edited');
        return to_route('newsItems.index');
    }

    public function destroy(Request $request, NewsItem $newsItem): RedirectResponse
    {
        $newsItem->delete();

        Session::flash("message", 'News item removed');
        return to_route('newsItems.index');
    }

    private function uploadAndResizeImage(Request $request, $newsItem): void
    {
        $headerFileName = $newsItem->id . '-header.' . $request->thumbnail->extension();
        $thumbnailFileName = $newsItem->id . '-thumbnail.' . $request->thumbnail->extension();

        $request->file('thumbnail')->storeAs('newsItems', $headerFileName, 'public');
        $newsItem->image_url = 'newsItems/' . $headerFileName;

        $request->file('thumbnail')->storeAs('newsItems', $thumbnailFileName, 'public');
        $newsItem->thumbnail_url = 'newsItems/' . $thumbnailFileName;
        $newsItem->save();

        //resize both images
        $this->resizeImage($newsItem->image_url, 1200, 500);
        $this->resizeImage($newsItem->thumbnail_url, 400, 300);
    }

    private function resizeImage(string $path, int $width, int $height): void
    {
        $imagePath = Storage::disk('public')->path($path);
        ImageManager::usingDriver(Driver::class)->decodePath($imagePath)->cover($width, $height)->save($imagePath);
    }

    private function validateInput(Request $request): array
    {
        return $request->validate([
            'title' => 'required|max:255',
            'text' => 'required',
            'author' => 'required|max:255',
        ]);
    }
}

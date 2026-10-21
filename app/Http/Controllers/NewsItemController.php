<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\NewsItem;
use App\Repositories\NewsItemRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class NewsItemController extends Controller
{
    public function __construct(private readonly NewsItemRepository $_newsItemRepository)
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Role::ContentAdministrator->value . ',' . Role::ActivityAdministrator->value);
    }

    public function index(): View
    {
        $newsItems = $this->_newsItemRepository->all(['id', 'title', 'created_at', 'author']);
        return view('beheer.newsItem.index', compact('newsItems'));
    }

    public function create(): View
    {
        $fields = ['title' => 'Add a news item ', 'method' => 'POST', 'url' => '/newsItems'];
        $newsItem = null;
        return view('beheer.newsItem.create_edit', compact('fields', 'newsItem'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validateInput($request);
        $newsItem = $this->_newsItemRepository->create($request->all());

        if ($request->hasFile('thumbnail')) {
            $this->uploadAndResizeImage($request, $newsItem);
        }

        Session::flash("message", 'News item added');
        return redirect('/newsItems');
    }

    public function show(Request $request, NewsItem $newsItem): View
    {
        return view('beheer.newsItem.show', compact('newsItem'));
    }

    public function edit(Request $request, NewsItem $newsItem): View
    {
        $fields = ['title' => 'Edit news item ', 'method' => 'PATCH', 'url' => '/newsItems/' . $newsItem->id];
        return view('beheer.newsItem.create_edit', compact('fields', 'newsItem'));
    }

    public function update(Request $request, NewsItem $newsItem): RedirectResponse
    {
        $this->validateInput($request);
        $this->_newsItemRepository->update($newsItem->id, $request->all());

        if ($request->hasFile('thumbnail')) {
            $this->uploadAndResizeImage($request, $newsItem);
        }

        Session::flash("message", 'News item edited');
        return redirect('/newsItems');
    }

    public function destroy(Request $request, NewsItem $newsItem): RedirectResponse
    {
        $this->_newsItemRepository->delete($newsItem->id);

        Session::flash("message", 'News item removed');
        return redirect('/newsItems');
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

    private function validateInput(Request $request): void
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'text' => 'required',
            'author' => 'required|max:255',
        ]);
    }
}

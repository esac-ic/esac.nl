<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HeaderSlideRequest;
use App\Models\HeaderSlide;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class HeaderSlideController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage-pages'),
        ];
    }

    public function index(): View
    {
        return view('admin.header-slides.index', ['headerSlides' => HeaderSlide::query()->with('media')->get()]);
    }

    public function create(): View
    {
        return view('admin.header-slides.form', ['headerSlide' => new HeaderSlide]);
    }

    public function store(HeaderSlideRequest $request): RedirectResponse
    {
        $headerSlide = HeaderSlide::query()->create($request->safe()->except('image'));
        $headerSlide->addMediaFromRequest('image')->toMediaCollection('image');

        return to_route('admin.header-slides.index')->with('message', 'Header slide added');
    }

    public function edit(HeaderSlide $headerSlide): View
    {
        return view('admin.header-slides.form', ['headerSlide' => $headerSlide]);
    }

    public function update(HeaderSlideRequest $request, HeaderSlide $headerSlide): RedirectResponse
    {
        $headerSlide->update($request->safe()->except('image'));
        if ($request->hasFile('image')) {
            $headerSlide->addMediaFromRequest('image')->toMediaCollection('image');
        }

        return to_route('admin.header-slides.index')->with('message', 'Header slide edited');
    }

    public function destroy(HeaderSlide $headerSlide): RedirectResponse
    {
        $headerSlide->delete();

        return to_route('admin.header-slides.index')->with('message', 'Header slide removed');
    }
}

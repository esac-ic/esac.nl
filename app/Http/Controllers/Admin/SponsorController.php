<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SponsorRequest;
use App\Models\Sponsor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SponsorController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage-pages'),
        ];
    }

    public function index(): View
    {
        return view('admin.sponsors.index', ['sponsors' => Sponsor::query()->with('media')->get()]);
    }

    public function create(): View
    {
        return view('admin.sponsors.form', ['sponsor' => new Sponsor]);
    }

    public function store(SponsorRequest $request): RedirectResponse
    {
        $sponsor = Sponsor::query()->create($request->safe()->except('logo'));
        $sponsor->addMediaFromRequest('logo')->toMediaCollection('logo');

        return to_route('admin.sponsors.index')->with('message', 'Sponsor added');
    }

    public function edit(Sponsor $sponsor): View
    {
        return view('admin.sponsors.form', ['sponsor' => $sponsor]);
    }

    public function update(SponsorRequest $request, Sponsor $sponsor): RedirectResponse
    {
        $sponsor->update($request->safe()->except('logo'));
        if ($request->hasFile('logo')) {
            $sponsor->addMediaFromRequest('logo')->toMediaCollection('logo');
        }

        return to_route('admin.sponsors.index')->with('message', 'Sponsor edited');
    }

    public function destroy(Sponsor $sponsor): RedirectResponse
    {
        $sponsor->delete();

        return to_route('admin.sponsors.index')->with('message', 'Sponsor removed');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ZekeringRequest;
use Illuminate\Http\RedirectResponse;

class ZekeringController extends Controller
{
    public function store(ZekeringRequest $request): RedirectResponse
    {
        $request->user()->zekeringen()->create($request->validated());

        return to_route('zekeringen');
    }
}

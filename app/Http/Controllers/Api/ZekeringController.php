<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ZekeringRequest;
use App\Http\Resources\ZekeringResource;
use App\Models\Zekering;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class ZekeringController extends Controller
{
    public function index(): array
    {
        $zekeringen = Zekering::query()->whereNull('parent_id')->latest('id')->with('children')->get();

        return ['zekeringen' => ZekeringResource::collection($zekeringen)];
    }

    public function store(ZekeringRequest $request): RedirectResponse
    {
        $request->user()->zekeringen()->create($request->validated());

        return to_route('zekeringen');
    }

    public function storeChild(ZekeringRequest $request): Response
    {
        $request->user()->zekeringen()->create($request->validated());

        return response(['msg' => 'Sub zekering created'], 200);
    }

    public function destroy(Zekering $zekering): Response
    {
        $zekering->delete();

        return response('', 200);
    }
}

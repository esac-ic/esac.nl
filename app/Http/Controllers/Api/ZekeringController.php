<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ZekeringRequest;
use App\Http\Resources\ZekeringResource;
use App\Models\Zekering;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ZekeringController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ZekeringResource::collection(Zekering::query()->whereNull('parent_id')->latest('id')->with('children')->paginate(10));
    }

    public function store(ZekeringRequest $request): ZekeringResource
    {
        return new ZekeringResource($request->user()->zekeringen()->create($request->validated()));
    }

    public function destroy(Zekering $zekering): Response
    {
        $zekering->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ZekeringRequest;
use App\Http\Resources\ZekeringResource;
use App\Models\Zekering;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ZekeringController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-zekeringen', only: ['destroy']),
        ];
    }

    public function getZekeringen(): array
    {
        $zekeringen = Zekering::query()->whereNull('parent_id')->latest('id')->with('children')->get();

        return ['zekeringen' => ZekeringResource::collection($zekeringen)];
    }

    public function storeZekering(ZekeringRequest $request): RedirectResponse
    {
        $request->user()->zekeringen()->create($request->validated());

        return to_route('front-end.zekeringen');
    }

    public function storeSubZekering(ZekeringRequest $request): Response
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

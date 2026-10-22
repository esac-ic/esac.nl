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
        $zekeringen = Zekering::query()->where('has_parent', false)->latest('id')->with('children')->get();

        return ['zekeringen' => ZekeringResource::collection($zekeringen)];
    }

    public function storeZekering(ZekeringRequest $request): RedirectResponse
    {
        $this->createZekering($request, $request->has('parent'), $request->input('parent'));

        return to_route('front-end.zekeringen');
    }

    public function storeSubZekering(ZekeringRequest $request): Response
    {
        $this->createZekering($request, true, $request->input('parent_id'));

        return response(['msg' => 'Sub zekering created'], 200);
    }

    public function destroy(int $id): Response
    {
        Zekering::destroy($id);

        return response('', 200);
    }

    private function createZekering(ZekeringRequest $request, bool $hasParent, $parentId): void
    {
        $zekering = new Zekering;
        $zekering->text = $request->input('text');
        $zekering->created_by = $request->user()->id;
        $zekering->score = 1;
        $zekering->parent_id = $hasParent ? $parentId : null;
        $zekering->has_parent = $hasParent;
        $zekering->save();
    }
}

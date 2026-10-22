<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuItemResource;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MenuItemController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-pages'),
        ];
    }

    public function index(Request $request): array
    {
        $menuItems = MenuItem::query()->when($request->has('type'), fn ($query) => $request->input('type') === 'subItem'
            ? $query->where('parent_id', $request->integer('parentId'))
            : $query->whereNull('parent_id'));

        return MenuItemResource::collection($menuItems->get())->resolve($request);
    }
}

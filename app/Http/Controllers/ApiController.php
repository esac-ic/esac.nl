<?php

namespace App\Http\Controllers;

use App\Http\Resources\MemberResource;
use App\Http\Resources\MenuItemResource;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ApiController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-pages', only: ['getMenuItems']),
            new Middleware('can:manage-mail-lists', only: ['getUsers']),
        ];
    }

    public function getMenuItems(Request $request): array
    {
        $menuItems = MenuItem::query();
        if ($request->has('type')) {
            if ($request->input('type') === 'subItem') {
                $menuItems->where('parent_id', $request->integer('parentId'));
            } else {
                $menuItems->whereNull('parent_id');
            }
        }

        return MenuItemResource::collection($menuItems->get())->resolve($request);
    }

    public function getUsers(): array
    {
        return ['aaData' => MemberResource::collection(User::current()->get(['id', 'email', 'firstname', 'preposition', 'lastname']))];
    }
}

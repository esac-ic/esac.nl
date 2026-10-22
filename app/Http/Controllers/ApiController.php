<?php

namespace App\Http\Controllers;

use App\Http\Resources\MemberResource;
use App\Http\Resources\MenuItemResource;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class ApiController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function getMenuItems(Request $request): array
    {
        $menuItemQeury = MenuItem::query();
        if ($request->has('type')) {
            if ($request->input('type') === "subItem") {
                $menuItemQeury->where('parent_id', '=', intval($request->input('parentId')));
            } else {
                $menuItemQeury->where('parent_id', '=', null);
            }
        }
        return MenuItemResource::collection($menuItemQeury->get())->resolve($request);
    }

    public function getUsers(): array
    {
        return ['aaData' => MemberResource::collection(User::current()->get(['id', 'email', 'firstname', 'preposition', 'lastname']))];
    }
}

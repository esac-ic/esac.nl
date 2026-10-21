<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function __construct(private readonly UserRepository $_userRepository)
    {
        $this->middleware('auth');
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
        $entries = $menuItemQeury->get();
        $menuItems = [];
        foreach ($entries as $menuItem) {
            array_push($menuItems, [
                "id" => $menuItem->id,
                "name" => $menuItem->name,
            ]);
        }
        return $menuItems;
    }

    public function getUsers(): array
    {
        $users = [];
        foreach ($this->_userRepository->getCurrentUsers(['id', 'email', 'firstname', 'preposition', 'lastname']) as $user) {
            array_push($users, [
                'id' => $user->id,
                'name' => $user->getName(),
                'email' => $user->email,
            ]);
        }

        return ['aaData' => $users];
    }
}

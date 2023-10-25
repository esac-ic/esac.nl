<?php

namespace App\Http\Controllers;

use App\MenuItem;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    private $_userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('auth');
        $this->_userRepository = $userRepository;
    }

    public function getMenuItems(Request $request)
    {
        $menuItemQeury = MenuItem::query();
        if ($request->has('type')) {
            if ($request->get('type') === "subItem") {
                $menuItemQeury->where('parent_id', '=', intval($request->get('parentId')));
            } else {
                $menuItemQeury->where('parent_id', '=', null);
            }
        }
        $entries = $menuItemQeury->get();
        $menuItems = array();
        foreach ($entries as $menuItem) {
            array_push($menuItems, [
                "id" => $menuItem->id,
                "name" => $menuItem->name,
            ]);
        }
        return $menuItems;
    }

    public function getUsers()
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

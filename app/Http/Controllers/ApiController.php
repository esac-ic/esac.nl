<?php

namespace App\Http\Controllers;

use App\AgendaItem;
use App\MenuItem;
use App\Repositories\RepositorieFactory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('getAgenda','getZekeringen');
    }

    public function getMenuItems(Request $request){
        $menuItemQeury = MenuItem::query();
        if($request->has('type')) {
            if($request->get('type') === "subItem"){
                $menuItemQeury->where('parent_id', '=', intval($request->get('parentId')));
            } else {
                $menuItemQeury->where('parent_id', '=', null);
            }
        }
        $entries = $menuItemQeury->get();
        $menuItems = array();
        foreach ($entries as $menuItem){
            array_push($menuItems, [
                "id"    =>  $menuItem->id,
                "name"  =>  $menuItem->text->text()
            ]);
        }
        return $menuItems;
    }



    public function getUsers(){
        $users = array();

        foreach (User::all() as $user){
            array_push($users,[
                'id'    => $user->id,
                'name'  => $user->getName(),
                'email' => $user->email,
            ]);
        }

        return ['aaData' => $users];
    }

}

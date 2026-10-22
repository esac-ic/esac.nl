<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

#[Fillable([
    'name',
    'parent_id',
    'url_name',
    'after',
    'menu_item',
    'deletable',
    'editable',
    'content',
])]
class MenuItem extends Model
{
    use SoftDeletes;

    const HOMEURL = 'home';
    const ZEKERINGURL = 'zekeringen';
    const SUBSCRIBEURL = 'signup';
    const NEWSURL = 'news';
    const MEMBERLISTURL = 'memberlist';
    const AGENDAURL = 'agenda';
    const LIBRARYURL = 'library';

    public function partner()
    {
        return $this->hasOne(MenuItem::class, 'id', 'parent_id');
    }

    public function afterItem()
    {
        return $this->hasOne(MenuItem::class, 'id', 'after');
    }

    //return the menu items wich aren't sub menus
    public function getMenuItems()
    {
        return MenuItem::query()->where('parent_id', '=', null)->where('menu_item', '=', true)->get();
    }

    public function getSubMenuItems($id)
    {
        return MenuItem::query()->where('parent_id', '=', $id)->where('menu_item', '=', true)->get();
    }

    public function show(): bool
    {
        if ($this->login) {
            if (Auth::guest()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return mixed[]
     */
    public function sortMeunuList($menuList): array
    {
        $newList = [];
        //get a menu item which is not after one
        $firstItem = $menuList->where('after', '=', null)->first();
        if ($firstItem == null) {
            if (count($menuList) == 0) {
                return $newList;
            }
            //there aren't any menu items witch don't have a menu after so we take the first item from te menu item
            $firstItem = $menuList[0];
        }

        array_push($newList, $firstItem);

        for ($i = 0; $i < count($newList); $i++) {
            $newItems = $menuList->where('after', '=', $newList[$i]->id);

            //check if there are new menu item which are after the previos menu item.
            if (count($newItems) === 0) {
                $newItems = $menuList->where('after', '=', null);
            }

            foreach ($newItems as $item) {
                if (!in_array($item, $newList)) {
                    array_push($newList, $item);
                }
            }
        }

        return $newList;
    }
}

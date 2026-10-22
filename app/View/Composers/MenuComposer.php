<?php

namespace App\View\Composers;

use App\Models\MenuItem;
use Illuminate\View\View;

class MenuComposer
{
    /**
     * Gives the menu its sorted top-level items and, per item id, its sorted sub items.
     */
    public function compose(View $view): void
    {
        $menuItems = MenuItem::sortMenuList(MenuItem::inMenu()->whereNull('parent_id')->get());

        $subMenuItems = [];
        foreach ($menuItems as $menuItem) {
            $subMenuItems[$menuItem->id] = MenuItem::sortMenuList(MenuItem::inMenu()->where('parent_id', $menuItem->id)->get());
        }

        $view->with(compact('menuItems', 'subMenuItems'));
    }
}

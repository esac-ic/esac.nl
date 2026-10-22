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
        $itemsByParent = MenuItem::query()->inMenu()->get()->groupBy('parent_id');

        $menuItems = MenuItem::sortMenuList($itemsByParent->get('', collect()));

        $view->with([
            'menuItems' => $menuItems,
            'subMenuItems' => collect($menuItems)->mapWithKeys(fn (MenuItem $item) => [
                $item->id => MenuItem::sortMenuList($itemsByParent->get($item->id, collect())),
            ])->all(),
        ]);
    }
}

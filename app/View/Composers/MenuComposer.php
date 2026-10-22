<?php

namespace App\View\Composers;

use App\Models\MenuItem;
use Illuminate\View\View;

class MenuComposer
{
    /**
     * Gives the menu its ordered top-level items and, per item id, its ordered child items.
     */
    public function compose(View $view): void
    {
        $itemsByParent = MenuItem::query()->inMenu()->ordered()->get()->groupBy('parent_id');

        $menuItems = $itemsByParent->get('', collect());

        $view->with([
            'menuItems' => $menuItems,
            'childMenuItems' => $menuItems->mapWithKeys(fn (MenuItem $item) => [
                $item->id => $itemsByParent->get($item->id, collect()),
            ])->all(),
        ]);
    }
}

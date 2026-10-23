<?php

namespace App\View\Composers;

use App\Models\MenuItem;
use Illuminate\View\View;

class MenuComposer
{
    /**
     * Gives the menu its ordered top-level items with their ordered child items.
     */
    public function compose(View $view): void
    {
        $view->with('menuItems', MenuItem::query()
            ->inMenu()
            ->whereNull('parent_id')
            ->with(['children' => fn ($query) => $query->inMenu()->ordered()])
            ->ordered()
            ->get());
    }
}

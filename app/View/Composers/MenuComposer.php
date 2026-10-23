<?php

namespace App\View\Composers;

use App\Models\Page;
use Illuminate\View\View;

class MenuComposer
{
    /**
     * Gives the menu its ordered top-level pages with their ordered sub pages.
     */
    public function compose(View $view): void
    {
        $view->with('pages', Page::query()
            ->inMenu()
            ->topLevel()
            ->with(['children' => fn ($query) => $query->inMenu()->ordered()])
            ->ordered()
            ->get());
    }
}

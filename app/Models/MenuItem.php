<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

#[Fillable([
    'name',
    'parent_id',
    'url_name',
    'after',
    'menu_item',
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

    protected function casts(): array
    {
        return [
            'login' => 'boolean',
            'menu_item' => 'boolean',
            'deletable' => 'boolean',
            'editable' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<MenuItem, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * @return BelongsTo<MenuItem, $this>
     */
    public function afterItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'after');
    }

    /**
     * Items that are shown in the menu.
     */
    #[Scope]
    protected function inMenu(Builder $query): void
    {
        $query->where('menu_item', true);
    }

    /**
     * Whether the current visitor may see the page.
     */
    public function show(): bool
    {
        return ! $this->login || Auth::check();
    }

    public static function sortMenuList($menuList): array
    {
        $newList = [];
        // get a menu item which is not after one
        $firstItem = $menuList->where('after', '=', null)->first();
        if ($firstItem == null) {
            if (count($menuList) == 0) {
                return $newList;
            }
            // there aren't any menu items witch don't have a menu after so we take the first item from te menu item
            $firstItem = $menuList[0];
        }

        array_push($newList, $firstItem);

        for ($i = 0; $i < count($newList); $i++) {
            $newItems = $menuList->where('after', '=', $newList[$i]->id);

            // check if there are new menu item which are after the previos menu item.
            if (count($newItems) === 0) {
                $newItems = $menuList->where('after', '=', null);
            }

            foreach ($newItems as $item) {
                if (! in_array($item, $newList)) {
                    array_push($newList, $item);
                }
            }
        }

        return $newList;
    }
}

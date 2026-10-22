<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name',
    'parent_id',
    'url_name',
    'position',
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
     * Items that are shown in the menu.
     */
    #[Scope]
    protected function inMenu(Builder $query): void
    {
        $query->where('menu_item', true);
    }

    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderBy('position')->orderBy('id');
    }
}

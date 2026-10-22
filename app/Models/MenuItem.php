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
    'slug',
    'position',
    'show_in_menu',
    'content',
])]
class MenuItem extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'requires_login' => 'boolean',
            'show_in_menu' => 'boolean',
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
        $query->where('show_in_menu', true);
    }

    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderBy('position')->orderBy('id');
    }
}

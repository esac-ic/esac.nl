<?php

namespace App\Models;

use App\Casts\SanitizedHtml;
use App\Enums\PageSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

#[Fillable([
    'name',
    'parent_id',
    'slug',
    'requires_login',
    'show_in_menu',
    'content',
])]
class MenuItem extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'content' => SanitizedHtml::class,
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
     * @return HasMany<MenuItem, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->chaperone('parent');
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

    /**
     * Whether the slug can only be changed in the database: the page isn't editable, or the site's own routes show its content.
     */
    public function hasFixedSlug(): bool
    {
        return $this->exists && (! $this->editable || PageSlug::tryFrom($this->slug) !== null);
    }

    /**
     * Moves the item one place up or down among its siblings and numbers them 1, 2, 3, ...
     */
    public function move(string $direction): void
    {
        $siblings = static::query()->where('parent_id', $this->parent_id)->ordered()->get()->values();
        $from = $siblings->search(fn (MenuItem $sibling) => $sibling->is($this));
        $to = $direction === 'up' ? $from - 1 : $from + 1;

        if ($siblings->has($to)) {
            [$siblings[$from], $siblings[$to]] = [$siblings[$to], $siblings[$from]];

            DB::transaction(fn () => $siblings->each(fn (MenuItem $sibling, int $index) => $sibling->forceFill(['position' => $index + 1])->save()));
        }
    }
}

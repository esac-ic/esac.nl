<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Orders the menu by a position number instead of a linked list of "after" ids.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->unsignedInteger('position')->default(0)->after('parent_id');
        });

        foreach (DB::table('menu_items')->orderBy('id')->get()->groupBy('parent_id') as $siblings) {
            // The menu showed the visible items in their linked-list order; the others follow.
            $visible = $siblings->filter(fn (object $item) => $this->isVisible($item))->values();
            $ordered = collect($this->linkedListOrder($visible))
                ->concat($this->linkedListOrder($siblings->values()))
                ->concat($siblings)
                ->unique('id')
                ->values();

            foreach ($ordered as $index => $item) {
                DB::table('menu_items')->where('id', $item->id)->update(['position' => ($index + 1) * 10]);
            }
        }

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('after');
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->unsignedInteger('after')->nullable()->after('editable');
        });

        foreach (DB::table('menu_items')->orderBy('position')->orderBy('id')->get()->groupBy('parent_id') as $siblings) {
            // Chain the visible items, so the old sort shows them in the same order.
            $previous = $previousVisible = null;
            foreach ($siblings as $item) {
                $visible = $this->isVisible($item);
                DB::table('menu_items')->where('id', $item->id)->update(['after' => $visible ? $previousVisible : $previous]);
                $previous = $item->id;
                $previousVisible = $visible ? $item->id : $previousVisible;
            }
        }

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }

    private function isVisible(object $item): bool
    {
        return $item->menu_item && $item->deleted_at === null;
    }

    /**
     * The order the removed MenuItem::sortMenuList gave the items.
     */
    private function linkedListOrder(Collection $items): array
    {
        $first = $items->where('after', '=', null)->first() ?? $items->first();
        if ($first === null) {
            return [];
        }

        $list = [$first];
        for ($i = 0; $i < count($list); $i++) {
            $next = $items->where('after', '=', $list[$i]->id);
            if ($next->isEmpty()) {
                $next = $items->where('after', '=', null);
            }
            foreach ($next as $item) {
                if (! in_array($item, $list)) {
                    $list[] = $item;
                }
            }
        }

        return $list;
    }
};

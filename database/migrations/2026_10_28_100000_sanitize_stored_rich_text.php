<?php

use App\Casts\SanitizedHtml;
use App\Models\Event;
use App\Models\MenuItem;
use App\Models\NewsItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Sanitizes the rich text that was stored before the models sanitized it on save.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->sanitize(new Event, 'text');
        $this->sanitize(new NewsItem, 'body');
        $this->sanitize(new MenuItem, 'content');
    }

    private function sanitize(Model $model, string $column): void
    {
        $cast = new SanitizedHtml;

        DB::table($model->getTable())->whereNotNull($column)->orderBy('id')->each(fn (object $row) => DB::table($model->getTable())
            ->where('id', $row->id)
            ->update([$column => $cast->set($model, $column, $row->{$column}, [])]));
    }
};

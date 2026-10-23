<?php

use App\Casts\SanitizedHtml;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Sanitizes the rich text that was stored before the models sanitized it on save.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->sanitize('events', 'text');
        $this->sanitize('news_items', 'body');
        $this->sanitize('menu_items', 'content');
    }

    private function sanitize(string $table, string $column): void
    {
        DB::table($table)->whereNotNull($column)->orderBy('id')->each(fn (object $row) => DB::table($table)
            ->where('id', $row->id)
            ->update([$column => SanitizedHtml::sanitize($row->{$column})]));
    }
};

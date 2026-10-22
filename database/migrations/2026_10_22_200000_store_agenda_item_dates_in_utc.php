<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * The agenda item dates were stored as the Amsterdam wall time the editors typed; this stores them in UTC.
 */
return new class extends Migration
{
    private const array COLUMNS = ['start_date', 'end_date', 'subscription_end_date'];

    public function up(): void
    {
        $this->convert('Europe/Amsterdam', 'UTC');
    }

    public function down(): void
    {
        $this->convert('UTC', 'Europe/Amsterdam');
    }

    /**
     * Converts every row on its own, so each gets the DST offset of its own date.
     */
    private function convert(string $from, string $to): void
    {
        DB::transaction(fn () => DB::table('agenda_items')->select(['id', ...self::COLUMNS])->chunkById(500, function ($agendaItems) use ($from, $to) {
            foreach ($agendaItems as $agendaItem) {
                DB::table('agenda_items')->where('id', $agendaItem->id)->update(
                    collect(self::COLUMNS)->mapWithKeys(fn (string $column) => [
                        $column => $agendaItem->$column === null
                            ? null
                            : Carbon::parse($agendaItem->$column, $from)->setTimezone($to)->toDateTimeString(),
                    ])->all()
                );
            }
        }));
    }
};

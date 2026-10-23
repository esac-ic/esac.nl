<?php

namespace App\Exports;

use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Cell\StringCell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * A single-sheet .xlsx export with a header row and columns sized to their contents.
 */
abstract class SpreadsheetExport
{
    private const int MAX_COLUMN_WIDTH = 60;

    /**
     * The rows of the sheet, with the cells in the same order as the headings.
     */
    abstract public function collection(): Collection;

    abstract public function title(): string;

    /**
     * @return list<string>
     */
    abstract public function headings(): array;

    /**
     * Map a single item of the collection to the cells of its row.
     */
    abstract public function map(mixed $row): array;

    public function download(string $fileName): StreamedResponse
    {
        $rows = $this->collection()
            ->map(fn ($row) => array_map($this->formatCell(...), array_values($this->map($row))))
            ->all();

        return response()->streamDownload(function () use ($rows) {
            $writer = SimpleExcelWriter::create(
                'php://output',
                'xlsx',
                fn (Writer $writer) => $this->setColumnWidths($writer, $rows),
            );

            $writer->nameCurrentSheet($this->sheetName());
            $writer->addHeader($this->headings());
            // OpenSpout would write text that starts with "=" as a formula, so text is always written as text.
            foreach ($rows as $cells) {
                $writer->addRow(new Row(array_map(fn ($value) => is_string($value) ? new StringCell($value) : Cell::fromValue($value), $cells)));
            }
            $writer->close();
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function formatCell(mixed $value): mixed
    {
        return $value instanceof DateTimeInterface ? Date::instance($value)->tz(config('app.display_timezone'))->format('Y-m-d H:i:s') : $value;
    }

    /**
     * Excel limits sheet names to 31 characters without []:*?/\
     */
    private function sheetName(): string
    {
        return mb_substr(str_replace(['[', ']', ':', '*', '?', '/', '\\'], '', $this->title()), 0, 31);
    }

    private function setColumnWidths(Writer $writer, array $rows): void
    {
        foreach ($this->headings() as $index => $heading) {
            $width = collect($rows)
                ->map(fn (array $row) => mb_strlen((string) ($row[$index] ?? '')))
                ->push(mb_strlen($heading))
                ->max();

            $writer->getOptions()->setColumnWidth(min($width + 2, self::MAX_COLUMN_WIDTH), $index + 1);
        }
    }
}

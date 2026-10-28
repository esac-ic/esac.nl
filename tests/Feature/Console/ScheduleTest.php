<?php

namespace Tests\Feature\Console;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    public static function weeklyCommands(): array
    {
        return [
            'remove:oldUsers' => ['remove:oldUsers'],
            'remove:oldApplications' => ['remove:oldApplications'],
            'auth:clear-resets' => ['auth:clear-resets'],
        ];
    }

    #[Test]
    #[DataProvider('weeklyCommands')]
    public function the_command_runs_weekly(string $command)
    {
        $events = collect($this->app->make(Schedule::class)->events())
            ->filter(fn (Event $event) => str_ends_with($event->command, "'artisan' $command"));

        $this->assertCount(1, $events, "$command is not scheduled");
        $this->assertSame('0 0 * * 0', $events->first()->expression);
    }
}

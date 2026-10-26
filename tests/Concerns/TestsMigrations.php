<?php

namespace Tests\Concerns;

use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * MySQL commits schema changes at once, so a migration test runs outside a transaction and the database is rebuilt after it.
 */
trait TestsMigrations
{
    use RefreshDatabase;

    protected function beginDatabaseTransaction(): void
    {
        $this->beforeApplicationDestroyed(fn () => $this->artisan('migrate:fresh'));
    }
}

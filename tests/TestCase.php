<?php

namespace Tests;

use App\Models\Rol;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Http::preventStrayRequests();
        $this->createRoles();
    }

    private function createRoles(): void
    {
        foreach (Config::get('constants') as $value) {
            if (! Rol::find($value)) {
                Rol::factory()->create([
                    'id' => $value,
                ]);
            }
        }
    }
}

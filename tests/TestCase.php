<?php

namespace Tests;

use App\Enums\Role;
use App\Models\Rol;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
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
        foreach (Role::cases() as $role) {
            if (! Rol::find($role->value)) {
                Rol::factory()->create([
                    'id' => $role->value,
                ]);
            }
        }
    }
}

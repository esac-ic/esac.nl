<?php

use App\Models\Rol;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Http::preventStrayRequests();
        $this->createRoles();
    }

    /**
     * @return void
     */
    private function createRoles(): void
    {
        foreach (Config::get('constants') as $key => $value) {
            if (!Rol::find($value))
            {
                Rol::factory()->create([
                    'id' => $value,
                ]);
            }
        }
    }
}

<?php

namespace Tests\Feature\Routing;

use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NamedRoutesTest extends TestCase
{
    #[Test]
    public function every_application_route_has_a_name()
    {
        $unnamed = collect(Route::getRoutes()->getRoutes())
            ->filter(fn ($route) => $route->getName() === null)
            ->map(fn ($route) => $route->uri())
            ->reject(fn ($uri) => in_array($uri, ['/', 'up']))
            ->values()
            ->all();

        $this->assertSame([], $unnamed);
    }
}

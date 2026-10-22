<?php

namespace Tests\Feature\Zekeringen;

use App\Models\User;
use App\Models\Zekering;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetZekeringenTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_api_lists_the_top_level_zekeringen_with_their_replies()
    {
        $zekering = Zekering::factory()->has(Zekering::factory(), 'children')->create();
        $other = Zekering::factory()->create();

        $this->actingAs(User::factory()->create())->get(route('api.zekeringen.index'))
            ->assertOk()
            ->assertJsonCount(2, 'zekeringen')
            ->assertJsonPath('zekeringen.0.id', $other->id)
            ->assertJsonPath('zekeringen.0.children', [])
            ->assertJsonPath('zekeringen.1.id', $zekering->id)
            ->assertJsonPath('zekeringen.1.children.0.id', $zekering->children->sole()->id);
    }
}

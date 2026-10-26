<?php

namespace Tests\Feature\Zekeringen;

use App\Models\User;
use App\Models\Zekering;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GetZekeringenTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_api_lists_the_top_level_zekeringen_with_their_replies()
    {
        $zekering = Zekering::factory()->has(Zekering::factory(), 'children')->create();
        $other = Zekering::factory()->create();

        $this->actingAs(User::factory()->create())->get(route('api.zekeringen.index'))
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', $other->id)
            ->assertJsonPath('data.0.children', [])
            ->assertJsonPath('data.0.can_reply', true)
            ->assertJsonPath('data.1.id', $zekering->id)
            ->assertJsonPath('data.1.children.0.id', $zekering->children->sole()->id)
            ->assertJsonPath('meta.last_page', 1);
    }
}

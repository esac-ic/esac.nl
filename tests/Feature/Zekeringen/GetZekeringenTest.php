<?php

namespace Tests\Feature\Zekeringen;

use App\Models\User;
use App\Models\Zekering;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TestCase;

class GetZekeringenTest extends TestCase
{
    use RefreshDatabase;

    private string $url = 'api/zekeringen';

    protected function setUp(): void
    {
        parent::setUp();

        $this->be(User::factory()->create());
        session()->start();
    }

    public function test_api_returns_zekeringen_with_their_sub_zekeringen()
    {
        $zekering = Zekering::factory()->create();
        $subZekering = Zekering::factory()->childOf($zekering)->create();
        $otherZekering = Zekering::factory()->create();

        $response = $this->get($this->url);
        $response->assertStatus(200);
        $zekeringen = collect($response->json('zekeringen'))->keyBy('id');

        $this->assertCount(2, $zekeringen);
        $this->assertEquals([$subZekering->id], array_column($zekeringen[$zekering->id]['children'], 'id'));
        $this->assertEmpty($zekeringen[$otherZekering->id]['children']);
    }

    public function test_zekering_that_is_its_own_parent_is_not_its_own_sub_zekering()
    {
        $zekering = Zekering::factory()->create();
        $zekering->parent_id = $zekering->id;
        $zekering->save();
        $subZekering = Zekering::factory()->childOf($zekering)->create();

        $response = $this->get($this->url);
        $response->assertStatus(200);
        $zekeringen = $response->json('zekeringen');

        $this->assertCount(1, $zekeringen);
        $this->assertEquals([$subZekering->id], array_column($zekeringen[0]['children'], 'id'));
    }
}

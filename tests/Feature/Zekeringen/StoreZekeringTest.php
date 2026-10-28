<?php

namespace Tests\Feature\Zekeringen;

use App\Models\User;
use App\Models\Zekering;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreZekeringTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_zekering_and_sub_zekering()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('api.zekeringen.store'), ['text' => 'Top'])->assertRedirect(route('front-end.zekeringen'));
        $parent = Zekering::sole();
        $this->actingAs($user)->post(route('api.subzekering.store'), ['text' => 'Reply', 'parent_id' => $parent->id])->assertOk();

        $this->assertEquals(
            [
                ['text' => 'Top', 'created_by' => $user->id, 'score' => 1, 'parent_id' => null, 'has_parent' => 0],
                ['text' => 'Reply', 'created_by' => $user->id, 'score' => 1, 'parent_id' => $parent->id, 'has_parent' => 1],
            ],
            Zekering::orderBy('id')->get(['text', 'created_by', 'score', 'parent_id', 'has_parent'])->toArray(),
        );
    }
}

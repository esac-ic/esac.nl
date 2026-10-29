<?php

namespace Tests\Feature\Zekeringen;

use App\Models\User;
use App\Models\Zekering;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StoreZekeringTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_member_can_post_a_zekering_and_a_reply()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->from(route('zekeringen'))->post(route('zekeringen.store'), ['text' => ' '])->assertRedirect(route('zekeringen'))->assertSessionHasErrors('text');
        $this->actingAs($user)->post(route('zekeringen.store'), ['text' => 'Top'])->assertRedirect(route('zekeringen'));
        $parent = Zekering::sole();
        $this->actingAs($user)->post(route('api.zekeringen.children.store'), ['text' => 'Reply', 'parent_id' => $parent->id])->assertCreated();

        $this->assertEquals(
            [
                ['text' => 'Top', 'created_by' => $user->id, 'parent_id' => null],
                ['text' => 'Reply', 'created_by' => $user->id, 'parent_id' => $parent->id],
            ],
            Zekering::query()->orderBy('id')->get(['text', 'created_by', 'parent_id'])->toArray(),
        );
    }
}

<?php

namespace Tests\Feature\User;

use App\CustomClasses\Eboekhouden\EboekhoudenFacade;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use TestCase;

class UpdateFinancialsTest extends TestCase
{
    use RefreshDatabase;

    public function test_iban_update()
    {
        $this->mock(EboekhoudenFacade::class, function (MockInterface $mock) {
            $mock->allows('syncRelation');
        });
        $user = User::factory()->create();
        $user->IBAN = "NL02ABNA0123456789";
        $user->save();

        $this->mock(EboekhoudenFacade::class, function (MockInterface $mock) {
            $mock->shouldReceive('syncRelation')->once();
        });
        $user = User::factory()->create();
        $user->IBAN = "BE71096123456769";
        $user->save();
    }

    public function test_mandate_update()
    {
        $this->mock(EboekhoudenFacade::class, function (MockInterface $mock) {
            $mock->allows('syncRelation');
        });
        $user = User::factory()->create();
        $user->incasso = true;
        $user->save();

        $this->mock(EboekhoudenFacade::class, function (MockInterface $mock) {
            $mock->shouldReceive('syncRelation')->once();
        });
        $user->incasso = false;
        $user->save();
    }
}

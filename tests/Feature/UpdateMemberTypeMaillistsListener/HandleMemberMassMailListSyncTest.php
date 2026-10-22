<?php

namespace Tests\Feature\UpdateMemberTypeMaillistsListener;

use App\Enums\SettingKey;
use App\Jobs\MemberMassMailListSyncJob;
use App\Models\Setting;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
use Mockery;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class HandleMemberMassMailListSyncTest extends TestCase
{
    use RefreshDatabase;

    private $mockedMailman;

    private User $user;

    private array $mailLists = [
        'member' => [
            'key' => SettingKey::NormalMemberMailLists->value,
            'value' => 'member.esac.nl',
        ],
        'extraordinary_member' => [
            'key' => SettingKey::ExtraordinaryMemberMailLists->value,
            'value' => 'extraordinary.esac.nl',
        ],
        'reunist' => [
            'key' => SettingKey::ReunistMemberMailLists->value,
            'value' => 'reunist.esac.nl',
        ],
        'honorary_member' => [
            'key' => SettingKey::HonoraryMemberMailLists->value,
            'value' => 'honorary.esac.nl',
        ],
        'member_of_merit' => [
            'key' => SettingKey::MeritMemberMailLists->value,
            'value' => 'merit.esac.nl',
        ],
        'trainer' => [
            'key' => SettingKey::TrainerMemberMailLists->value,
            'value' => 'trainer.esac.nl',
        ],
        'relationship' => [
            'key' => SettingKey::RelationshipMemberMailLists->value,
            'value' => 'relationship.esac.nl',
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockedMailman = Mockery::mock(Mailman::class);
        $this->user = User::factory()->member()->create();

        Setting::truncate();
        Setting::insert(collect($this->mailLists)->map(fn ($set) => new Setting(['name' => $set['key'], 'type' => Setting::TYPE_STRING, 'value' => $set['value']]))->values()->toArray());
    }

    public function test_add_single_normal_member()
    {
        $this->mockedMailman->shouldNotReceive('removeUserFromSpecifiedMailLists');
        $this->mockedMailman->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), ['member.esac.nl'])
            ->once();

        $this->user->kind_of_member = Lang::get('member');
        $this->user->save();

        $job = new MemberMassMailListSyncJob(User::all());
        $job->handle($this->mockedMailman);
    }

    // Reasonable order of magnitude for a big mail list
    public function test_add_200_members()
    {
        $this->user->kind_of_member = Lang::get('member');
        $this->user->save();
        User::factory()->member()->count(199)->create();

        $this->mockedMailman->shouldNotReceive('removeUserFromSpecifiedMailLists');

        foreach (User::all() as $user) {
            $this->mockedMailman->shouldReceive('addUserToSpecifiedMailLists')
                ->with($user->email, $user->getName(), ['member.esac.nl'])
                ->once();
        }

        $job = new MemberMassMailListSyncJob(User::all());

        $job->handle($this->mockedMailman);
    }

    public function test_add_mix_of_members()
    {
        $this->user->kind_of_member = Lang::get('member');
        $this->user->save();

        User::factory()->reunist()->create();
        User::factory()->extraordinary_participant()->create();
        User::factory()->honorary_member()->create();
        User::factory()->member_of_merit()->create();
        User::factory()->trainer()->create();
        User::factory()->relationship()->create();

        $this->mockedMailman->shouldNotReceive('removeUserFromSpecifiedMailLists');

        $this->mockedMailman->shouldReceive('addUserToSpecifiedMailLists')
            ->withArgs(function ($email, $name, $lists) {
                assertEquals([
                    $this->mailLists[User::firstWhere('email', $email)->kind_of_member]['value'],
                ], $lists);

                return true;
            })
            ->times(User::count());

        $job = new MemberMassMailListSyncJob(User::all());
        $job->handle($this->mockedMailman);
    }
}

<?php

namespace Tests\Feature\UpdateMemberTypeMaillistsListener;

use App\Enums\SettingKey;
use App\Events\PendingUserApproved;
use App\Listeners\UpdateMemberTypeMaillists;
use App\Models\Setting;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
use Mockery;
use Tests\TestCase;

class HandlePendingUserApprovedTest extends TestCase
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
        Setting::insert(collect($this->mailLists)->map(fn ($set) => new Setting([
            'name' => $set['key'],
            'type' => Setting::TYPE_STRING,
            'value' => $set['value'],
        ]))->values()->toArray());
    }

    /*
     * Happy flow tests, there is not really an error flow
     */
    public function test_pending_member_becomes_normal_member()
    {
        $this->mockedMailman->shouldNotReceive('removeUserFromSpecifiedMailLists');

        $this->mockedMailman->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['member']['value']])
            ->once();

        $this->user->kind_of_member = Lang::get('member');
        $this->user->save();

        $listener = new UpdateMemberTypeMaillists($this->mockedMailman);
        $event = new PendingUserApproved($this->user);

        $listener->handlePendingUserApproved($event);
    }

    public function test_pending_member_becomes_reunist()
    {
        $this->mockedMailman->shouldNotReceive('removeUserFromSpecifiedMailLists');

        $this->mockedMailman->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['reunist']['value']])
            ->once();

        $this->user->kind_of_member = Lang::get('reunist');
        $this->user->save();

        $listener = new UpdateMemberTypeMaillists($this->mockedMailman);
        $event = new PendingUserApproved($this->user);

        $listener->handlePendingUserApproved($event);
    }

    public function test_pending_member_becomes_extraordinary_participant()
    {
        $this->mockedMailman->shouldNotReceive('removeUserFromSpecifiedMailLists');

        $this->mockedMailman->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['extraordinary_member']['value']])
            ->once();

        $this->user->kind_of_member = Lang::get('extraordinary_member');
        $this->user->save();

        $listener = new UpdateMemberTypeMaillists($this->mockedMailman);
        $event = new PendingUserApproved($this->user);

        $listener->handlePendingUserApproved($event);
    }

    public function test_pending_member_becomes_honorary_member()
    {
        $this->mockedMailman->shouldNotReceive('removeUserFromSpecifiedMailLists');

        $this->mockedMailman->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['honorary_member']['value']])
            ->once();

        $this->user->kind_of_member = Lang::get('honorary_member');
        $this->user->save();

        $listener = new UpdateMemberTypeMaillists($this->mockedMailman);
        $event = new PendingUserApproved($this->user);

        $listener->handlePendingUserApproved($event);
    }

    public function test_pending_member_becomes_member_of_merit()
    {
        $this->mockedMailman->shouldNotReceive('removeUserFromSpecifiedMailLists');

        $this->mockedMailman->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['member_of_merit']['value']])
            ->once();

        $this->user->kind_of_member = Lang::get('member_of_merit');
        $this->user->save();

        $listener = new UpdateMemberTypeMaillists($this->mockedMailman);
        $event = new PendingUserApproved($this->user);

        $listener->handlePendingUserApproved($event);
    }

    public function test_pending_member_becomes_trainer()
    {
        $this->mockedMailman->shouldNotReceive('removeUserFromSpecifiedMailLists');

        $this->mockedMailman->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['trainer']['value']])
            ->once();

        $this->user->kind_of_member = Lang::get('trainer');
        $this->user->save();

        $listener = new UpdateMemberTypeMaillists($this->mockedMailman);
        $event = new PendingUserApproved($this->user);

        $listener->handlePendingUserApproved($event);
    }

    public function test_pending_member_becomes_relationship()
    {
        $this->mockedMailman->shouldNotReceive('removeUserFromSpecifiedMailLists');

        $this->mockedMailman->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['relationship']['value']])
            ->once();

        $this->user->kind_of_member = Lang::get('relationship');
        $this->user->save();

        $listener = new UpdateMemberTypeMaillists($this->mockedMailman);
        $event = new PendingUserApproved($this->user);

        $listener->handlePendingUserApproved($event);
    }
}

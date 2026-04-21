<?php

namespace Tests\Feature\UpdateMemberTypeMaillistsListener;

use App\CustomClasses\MailList\MailListFacade;
use App\Events\MemberMassMailListSync;
use App\Jobs\MemberMassMailListSyncJob;
use App\Listeners\UpdateMemberTypeMaillists;
use App\Setting;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
use Mockery;
use function PHPUnit\Framework\assertEquals;

class HandleMemberMassMailListSyncTest extends \TestCase
{
    use RefreshDatabase;
    
    private $mockedMailListFacade;
    private User  $user;
    private array $mailLists = [
        'member' => [
            'key' => Setting::SETTING_NORMAL_MEMBER_MAIL_LISTS,
            'value' => 'member.esac.nl',
        ],
        'extraordinary_member' => [
            'key' => Setting::SETTING_EXTRAORDINARY_MEMBER_MAIL_LISTS,
            'value' => 'extraordinary.esac.nl',
        ],
        'reunist' => [
            'key' => Setting::SETTING_REUNIST_MEMBER_MAIL_LISTS,
            'value' => 'reunist.esac.nl',
        ],
        'honorary_member' => [
            'key' => Setting::SETTING_HONORARY_MEMBER_MAIL_LISTS,
            'value' => 'honorary.esac.nl',
        ],
        'member_of_merit' => [
            'key' => Setting::SETTING_MERIT_MEMBER_MAIL_LISTS,
            'value' => 'merit.esac.nl',
        ],
        'trainer' => [
            'key' => Setting::SETTING_TRAINER_MEMBER_MAIL_LISTS,
            'value' => 'trainer.esac.nl',
        ],
        'relationship' => [
            'key' => Setting::SETTING_RELATIONSHIP_MEMBER_MAIL_LISTS,
            'value' => 'relationship.esac.nl',
        ],
    ];
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockedMailListFacade = Mockery::mock(MailListFacade::class);
        $this->user = User::factory()->member()->create();

        Setting::truncate();
        Setting::insert(collect($this->mailLists)->map(fn ($set) => new Setting(['name' => $set['key'], 'type' => Setting::TYPE_STRING, 'value' => $set['value']]))->values()->toArray());
    }
    
    public function test_add_single_normal_member()
    {
        $this->mockedMailListFacade->shouldNotReceive('removeUserFromSpecifiedMailLists');
        
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), ['member.esac.nl'])
            ->once();

        $this->user->kind_of_member = Lang::get('member');
        $this->user->save();

//        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
//        $event = new MemberMassMailListSync(User::all());
//
//        $listener->handleMemberMassMailListSync($event);
        MemberMassMailListSyncJob::dispatch(User::all());
    }

    //Reasonable order of magnitude for a big mail list
    public function test_add_200_members()
    {
        $this->user->kind_of_member = Lang::get('member');
        $this->user->save();
        User::factory()->member()->count(199)->create();


        $this->mockedMailListFacade->shouldNotReceive('removeUserFromSpecifiedMailLists');

        foreach (User::all() as $user)
        {
            $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
                ->with($user->email, $user->getName(), ['member.esac.nl'])
                ->once();
        }
        
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberMassMailListSync(User::all());
        
        $listener->handleMemberMassMailListSync($event);
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
        
        $this->mockedMailListFacade->shouldNotReceive('removeUserFromSpecifiedMailLists');


        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->withArgs(function ($email, $name, $lists) {
                assertEquals([
                    $this->mailLists[User::firstWhere('email', $email)->kind_of_member]['value']
                ], $lists);
                return true;
            })
            ->times(User::count());

        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberMassMailListSync(User::all());
        
        $listener->handleMemberMassMailListSync($event);
    }
}

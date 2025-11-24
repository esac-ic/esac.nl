<?php

namespace Tests\Feature;

use App\CustomClasses\MailList\MailListFacade;
use App\Events\MemberMassMailListSync;
use App\Events\OldMemberBecameMember;
use App\Listeners\UpdateMemberTypeMaillists;
use App\Setting;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
use Mockery;

class HandleMemberMassMailListSyncTest extends \TestCase
{
    use RefreshDatabase;
    
    private $mockedMailListFacade;
    private User  $user;
    private array $mailLists = [
        'member' => 'member.esac.nl',
        'reunist' => 'reunist.esac.nl',
        'extraordinary_member' => 'extraordinary.esac.nl',
        'honorary_member' => 'honorary.esac.nl',
        'member_of_merit' => 'merit.esac.nl',
        'trainer' => 'trainer.esac.nl',
        'relationship' => 'relationship.esac.nl',
    ];
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockedMailListFacade = Mockery::mock(MailListFacade::class);
        $this->user = User::factory()->member()->create();
        
        $setting = new Setting(['name' => Setting::SETTING_NORMAL_MEMBER_MAIL_LISTS, 'type' => Setting::TYPE_STRING, 'value' => 'member']);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_EXTRAORDINARY_MEMBER_MAIL_LISTS, 'type' => Setting::TYPE_STRING, 'value' => 'extraordinary']);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_REUNIST_MEMBER_MAIL_LISTS, 'type' => Setting::TYPE_STRING, 'value' => 'reunist']);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_HONORARY_MEMBER_MAIL_LISTS, 'type' => Setting::TYPE_STRING, 'value' => 'honorary']);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_MERIT_MEMBER_MAIL_LISTS, 'type' => Setting::TYPE_STRING, 'value' => 'merit']);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_TRAINER_MEMBER_MAIL_LISTS, 'type' => Setting::TYPE_STRING, 'value' => 'trainer']);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_RELATIONSHIP_MEMBER_MAIL_LISTS, 'type' => Setting::TYPE_STRING, 'value' => 'relationship']);
        $setting->save();
    }
    
    public function test_add_single_normal_member()
    {
        $this->mockedMailListFacade->shouldNotReceive('removeUserFromSpecifiedMailLists');
        
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['member']])
            ->once();
        
        $this->user->kind_of_member = Lang::get('member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberMassMailListSync(User::all());
        
        $listener->handleMemberMassMailListSync($event);
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
                ->with($user->email, $user->getName(), [$this->mailLists['member']])
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
        
        foreach (User::all() as $user)
        {
            $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
                ->with($user->email, $user->getName(), [$this->mailLists[$user->kind_of_member]])
                ->once();
        }
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberMassMailListSync(User::all());
        
        $listener->handleMemberMassMailListSync($event);
    }
}

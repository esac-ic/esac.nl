<?php

namespace Tests\Feature;

use App\CustomClasses\MailList\MailListFacade;
use App\Events\MemberTypeChanged;
use App\Listeners\UpdateMemberTypeMaillists;
use App\Setting;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class UpdateMemberTypeMaillistsListenerTest extends \TestCase
{
    use RefreshDatabase;
    
    private $mockedMailListFacade;
    private User  $user;
    private array $mailLists = [
        'member' => 'member.esac.nl',
        'reunist' => 'reunist.esac.nl',
        'extraordinary' => 'extraordinary.esac.nl',
        'honorary' => 'honorary.esac.nl',
        'merit' => 'merit.esac.nl',
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
    
    public function test_handles_multiple_mail_lists_correctly()
    {
        $memberSetting = Setting::where('name', Setting::SETTING_NORMAL_MEMBER_MAIL_LISTS)->first();
        $memberSetting->value = 'first-member;second-member;third-member;fourth-member';
        $memberSetting->save();
        
        $reunistSetting = Setting::where('name', Setting::SETTING_REUNIST_MEMBER_MAIL_LISTS)->first();
        $reunistSetting->value = 'first-reunist;second-reunist;third-reunist;fourth-reunist';
        $reunistSetting->save();
        
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), ['first-reunist.esac.nl', 'second-reunist.esac.nl',  'third-reunist.esac.nl',  'fourth-reunist.esac.nl'])
            ->once();
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, ['first-member.esac.nl', 'second-member.esac.nl', 'third-member.esac.nl',  'fourth-member.esac.nl'])
            ->once();
        
        $event = new MemberTypeChanged($this->user, \Lang::get('member'), \Lang::get('reunist'));
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_member_to_reunist()
    {
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['reunist']])
            ->once();
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['member']])
            ->once();

        $this->user->kind_of_member = \Lang::get('member');
        $this->user->save();

        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, \Lang::get('member'), \Lang::get('reunist'));

        $listener->handleMemberTypeChanged($event);
    }
}

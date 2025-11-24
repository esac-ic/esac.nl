<?php

namespace Tests\Feature\UpdateMemberTypeMaillistsListener;

use App\CustomClasses\MailList\MailListFacade;
use App\Events\MemberTypeChanged;
use App\Listeners\UpdateMemberTypeMaillists;
use App\Setting;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
use Mockery;

class HandleMemberTypeChangedTest extends \TestCase
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
        
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, ['first-member.esac.nl', 'second-member.esac.nl', 'third-member.esac.nl',  'fourth-member.esac.nl'])
            ->once()
            ->ordered(); //should remove people from maillists before adding them to the new ones in case there is overlap
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), ['first-reunist.esac.nl', 'second-reunist.esac.nl', 'third-reunist.esac.nl', 'fourth-reunist.esac.nl'])
            ->once()
            ->ordered();
        
        $event = new MemberTypeChanged($this->user, Lang::get('member'), Lang::get('reunist'));
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $listener->handleMemberTypeChanged($event);
    }
    
    /*
     * These test cases should all be pretty much the same, but they just test the different combinations
     */
    
    /*
     * User -> ...
     */
    
    public function test_mail_lists_updated_member_to_reunist()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['member']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['reunist']])
            ->once()
            ->ordered();

        $this->user->kind_of_member = Lang::get('member');
        $this->user->save();

        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('member'), Lang::get('reunist'));

        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_member_to_extraordinary()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['member']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['extraordinary']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('member'), Lang::get('extraordinary_member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_member_to_honorary()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['member']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['honorary']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('member'), Lang::get('honorary_member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_member_to_merit()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['member']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['merit']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('member'), Lang::get('member_of_merit'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_member_to_trainer()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['member']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['trainer']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('member'), Lang::get('trainer'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_member_to_relationship()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['member']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['relationship']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('member'), Lang::get('relationship'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    /*
     * Reunist -> ...
     */
    
    public function test_mail_lists_updated_reunist_to_member()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['reunist']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['member']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('reunist');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('reunist'), Lang::get('member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_reunist_to_extraordinary()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['reunist']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['extraordinary']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('reunist');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('reunist'), Lang::get('extraordinary_member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_reunist_to_honorary()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['reunist']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['honorary']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('reunist');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('reunist'), Lang::get('honorary_member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_reunist_to_merit()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['reunist']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['merit']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('reunist');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('reunist'), Lang::get('member_of_merit'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_reunist_to_trainer()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['reunist']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['trainer']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('reunist');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('reunist'), Lang::get('trainer'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_reunist_to_relationship()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['reunist']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['relationship']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('reunist');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('reunist'), Lang::get('relationship'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    /*
     * Extraordinary participant -> ...
     */
    
    public function test_mail_lists_updated_extraordinary_to_member()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['extraordinary']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['member']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('extraordinary_member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('extraordinary_member'), Lang::get('member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_extraordinary_to_reunist()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['extraordinary']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['reunist']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('extraordinary_member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('extraordinary_member'), Lang::get('reunist'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_extraordinary_to_honorary()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['extraordinary']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['honorary']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('extraordinary_member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('extraordinary_member'), Lang::get('honorary_member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_extraordinary_to_merit()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['extraordinary']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['merit']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('extraordinary_member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('extraordinary_member'), Lang::get('member_of_merit'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_extraordinary_to_trainer()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['extraordinary']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['trainer']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('extraordinary_member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('extraordinary_member'), Lang::get('trainer'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_extraordinary_to_relationship()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['extraordinary']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['relationship']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('extraordinary_member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('extraordinary_member'), Lang::get('relationship'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    /*
     * Honorary member -> ...
     */
    
    public function test_mail_lists_updated_honorary_to_member()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['honorary']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['member']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('honorary_member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('honorary_member'), Lang::get('member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_honorary_to_reunist()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['honorary']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['reunist']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('honorary_member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('honorary_member'), Lang::get('reunist'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_honorary_to_extraordinary()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['honorary']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['extraordinary']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('honorary_member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('honorary_member'), Lang::get('extraordinary_member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_honorary_to_merit()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['honorary']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['merit']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('honorary_member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('honorary_member'), Lang::get('member_of_merit'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_honorary_to_trainer()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['honorary']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['trainer']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('honorary_member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('honorary_member'), Lang::get('trainer'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_honorary_to_relationship()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['honorary']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['relationship']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('honorary_member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('honorary_member'), Lang::get('relationship'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    /*
     * Member of merit -> ...
     */
    
    public function test_mail_lists_updated_merit_to_member()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['merit']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['member']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('member_of_merit');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('member_of_merit'), Lang::get('member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_merit_to_reunist()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['merit']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['reunist']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('member_of_merit');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('member_of_merit'), Lang::get('reunist'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_merit_to_extraordinary()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['merit']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['extraordinary']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('member_of_merit');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('member_of_merit'), Lang::get('extraordinary_member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_merit_to_honorary()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['merit']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['honorary']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('member_of_merit');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('member_of_merit'), Lang::get('honorary_member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_merit_to_trainer()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['merit']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['trainer']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('member_of_merit');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('member_of_merit'), Lang::get('trainer'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_merit_to_relationship()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['merit']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['relationship']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('member_of_merit');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('member_of_merit'), Lang::get('relationship'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    /*
     * Trainer -> ...
     */
    
    public function test_mail_lists_updated_trainer_to_member()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['trainer']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['member']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('trainer');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('trainer'), Lang::get('member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_trainer_to_reunist()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['trainer']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['reunist']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('trainer');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('trainer'), Lang::get('reunist'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_trainer_to_extraordinary()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['trainer']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['extraordinary']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('trainer');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('trainer'), Lang::get('extraordinary_member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_trainer_to_honorary()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['trainer']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['honorary']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('trainer');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('trainer'), Lang::get('honorary_member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_trainer_to_merit()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['trainer']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['merit']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('trainer');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('trainer'), Lang::get('member_of_merit'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_trainer_to_relationship()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['trainer']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['relationship']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('trainer');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('trainer'), Lang::get('relationship'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    /*
     * Relationship -> ...
     */
    
    public function test_mail_lists_updated_relation_to_member()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['relationship']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['member']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('relationship');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('relationship'), Lang::get('member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_relation_to_reunist()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['relationship']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['reunist']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('relationship');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('relationship'), Lang::get('reunist'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_relation_to_extraordinary()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['relationship']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['extraordinary']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('relationship');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('relationship'), Lang::get('extraordinary_member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_relation_to_honorary()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['relationship']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['honorary']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('relationship');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('relationship'), Lang::get('honorary_member'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_relation_to_merit()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['relationship']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['merit']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('relationship');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('relationship'), Lang::get('member_of_merit'));
        
        $listener->handleMemberTypeChanged($event);
    }
    
    public function test_mail_lists_updated_relation_to_trainer()
    {
        $this->mockedMailListFacade->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailLists['relationship']])
            ->once()
            ->ordered();
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['trainer']])
            ->once()
            ->ordered();
        
        $this->user->kind_of_member = Lang::get('relationship');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new MemberTypeChanged($this->user, Lang::get('relationship'), Lang::get('trainer'));
        
        $listener->handleMemberTypeChanged($event);
    }
}

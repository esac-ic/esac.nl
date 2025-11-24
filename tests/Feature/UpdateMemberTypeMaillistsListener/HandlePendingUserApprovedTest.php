<?php

namespace Tests\Feature\UpdateMemberTypeMaillistsListener;

use App\CustomClasses\MailList\MailListFacade;
use App\Events\PendingUserApproved;
use App\Listeners\UpdateMemberTypeMaillists;
use App\Setting;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
use Mockery;

class HandlePendingUserApprovedTest extends \TestCase
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
    
    /*
     * Happy flow tests, there is not really an error flow
     */
    public function test_pending_member_becomes_normal_member()
    {
        $this->mockedMailListFacade->shouldNotReceive('removeUserFromSpecifiedMailLists');
        
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['member']])
            ->once();
        
        $this->user->kind_of_member = Lang::get('member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new PendingUserApproved($this->user);
        
        $listener->handlePendingUserApproved($event);
    }
    
    public function test_pending_member_becomes_reunist()
    {
        $this->mockedMailListFacade->shouldNotReceive('removeUserFromSpecifiedMailLists');
        
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['reunist']])
            ->once();
        
        $this->user->kind_of_member = Lang::get('reunist');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new PendingUserApproved($this->user);
        
        $listener->handlePendingUserApproved($event);
    }
    
    public function test_pending_member_becomes_extraordinary_participant()
    {
        $this->mockedMailListFacade->shouldNotReceive('removeUserFromSpecifiedMailLists');
        
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['extraordinary_member']])
            ->once();
        
        $this->user->kind_of_member = Lang::get('extraordinary_member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new PendingUserApproved($this->user);
        
        $listener->handlePendingUserApproved($event);
    }
    
    public function test_pending_member_becomes_honorary_member()
    {
        $this->mockedMailListFacade->shouldNotReceive('removeUserFromSpecifiedMailLists');
        
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['honorary_member']])
            ->once();
        
        $this->user->kind_of_member = Lang::get('honorary_member');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new PendingUserApproved($this->user);
        
        $listener->handlePendingUserApproved($event);
    }
    
    public function test_pending_member_becomes_member_of_merit()
    {
        $this->mockedMailListFacade->shouldNotReceive('removeUserFromSpecifiedMailLists');
        
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['member_of_merit']])
            ->once();
        
        $this->user->kind_of_member = Lang::get('member_of_merit');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new PendingUserApproved($this->user);
        
        $listener->handlePendingUserApproved($event);
    }
    
    public function test_pending_member_becomes_trainer()
    {
        $this->mockedMailListFacade->shouldNotReceive('removeUserFromSpecifiedMailLists');
        
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['trainer']])
            ->once();
        
        $this->user->kind_of_member = Lang::get('trainer');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new PendingUserApproved($this->user);
        
        $listener->handlePendingUserApproved($event);
    }
    
    public function test_pending_member_becomes_relationship()
    {
        $this->mockedMailListFacade->shouldNotReceive('removeUserFromSpecifiedMailLists');
        
        $this->mockedMailListFacade->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailLists['relationship']])
            ->once();
        
        $this->user->kind_of_member = Lang::get('relationship');
        $this->user->save();
        
        $listener = new UpdateMemberTypeMaillists($this->mockedMailListFacade);
        $event = new PendingUserApproved($this->user);
        
        $listener->handlePendingUserApproved($event);
    }
}

<?php

namespace Tests\Feature\EventLogListeners;

use App\Enums\UserEventTypes;
use App\Events\MemberKindChanged;
use App\Listeners\LogMemberKindChanged;
use App\Models\UserEventLogEntry;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
use InvalidArgumentException;

class LogMemberTypeChangedTest extends \TestCase
{
    use RefreshDatabase;
    
    //made the private vars because they are less annoying to type than Lang::get('') with my ide autocomplete
    private $memberStr;
    private $reunistStr;
    private  $extraordinaryMemberStr;
    private  $honoraryMemberStr;
    private $memberOfMeritStr;
    private $trainerStr;
    private $relationshipStr;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->memberStr = Lang::get('member');
        $this->reunistStr = Lang::get('reunist');
        $this->extraordinaryMemberStr = Lang::get('extraordinary_member');
        $this->honoraryMemberStr = Lang::get('honorary_member');
        $this->memberOfMeritStr = Lang::get('member_of_merit');
        $this->trainerStr = Lang::get('trainer');
        $this->relationshipStr = Lang::get('relationship');
    }
    
    /**
     * Test for a user the log member type changed function.
     *
     * @param User $user
     * @param string $from must match $user membership type
     * @param string $to
     *
     * @return void
     */
    private function test_log(User $user, string $from, string $to)
    {
        if ($user->kind_of_member != $from)
        {
            throw new InvalidArgumentException("Error: user membership type didn't match from argument");
        }
        
        $event = new MemberKindChanged($user, $from, $to);
        
        $this->assertEquals(0, UserEventLogEntry::all()->count());
        
        $listener = new LogMemberKindChanged();
        $listener->handle($event);
        
        $this->assertEquals(1, UserEventLogEntry::all()->count());
        $logEntry = UserEventLogEntry::all()->first();
        
        //assert correct event format
        $this->assertTrue($user->is($logEntry->user));
        $this->assertEquals(UserEventTypes::MemberTypeChanged->value,  $logEntry->eventType);
        $this->assertEquals($user->getName() . " changed from " . $from . " to " . $to, $logEntry->eventDetails);
    }
    
    
    /*
     * Member to .. tests
     */
    public function test_member_becomes_reunist()
    {
        $from = $this->memberStr; //convenience for replicating the tests
        $to = $this->reunistStr;
        
        $user = User::factory()->member()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_member_becomes_extraordinary_member()
    {
        $from = $this->memberStr; //convenience for replicating the tests
        $to = $this->extraordinaryMemberStr;
        
        $user = User::factory()->member()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_member_becomes_honorary_member()
    {
        $from = $this->memberStr; //convenience for replicating the tests
        $to = $this->honoraryMemberStr;
        
        $user = User::factory()->member()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_member_becomes_member_of_merit()
    {
        $from = $this->memberStr; //convenience for replicating the tests
        $to = $this->memberOfMeritStr;
        
        $user = User::factory()->member()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_member_becomes_trainer()
    {
        $from = $this->memberStr; //convenience for replicating the tests
        $to = $this->trainerStr;
        
        $user = User::factory()->member()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_member_becomes_relationship()
    {
        $from = $this->memberStr; //convenience for replicating the tests
        $to = $this->relationshipStr;
        
        $user = User::factory()->member()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    /*
     * Reunist to ... tests
     */
    
    public function test_reunist_becomes_member()
    {
        $from = $this->reunistStr; //convenience for replicating the tests
        $to = $this->memberStr;
        
        $user = User::factory()->reunist()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_reunist_becomes_extraordinary_member()
    {
        $from = $this->reunistStr; //convenience for replicating the tests
        $to = $this->extraordinaryMemberStr;
        
        $user = User::factory()->reunist()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_reunist_becomes_honorary_member()
    {
        $from = $this->reunistStr; //convenience for replicating the tests
        $to = $this->honoraryMemberStr;
        
        $user = User::factory()->reunist()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_reunist_becomes_member_of_merit()
    {
        $from = $this->reunistStr; //convenience for replicating the tests
        $to = $this->memberOfMeritStr;
        
        $user = User::factory()->reunist()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_reunist_becomes_trainer()
    {
        $from = $this->reunistStr; //convenience for replicating the tests
        $to = $this->trainerStr;
        
        $user = User::factory()->reunist()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_reunist_becomes_relationship()
    {
        $from = $this->reunistStr; //convenience for replicating the tests
        $to = $this->relationshipStr;
        
        $user = User::factory()->reunist()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    /*
     * Extraordinary participant to ... tests
     */
    
    public function test_extraordinary_member_becomes_member()
    {
        $from = $this->extraordinaryMemberStr; //convenience for replicating the tests
        $to = $this->memberStr;
        
        $user = User::factory()->extraordinary_participant()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_extraordinary_member_becomes_reunist()
    {
        $from = $this->extraordinaryMemberStr; //convenience for replicating the tests
        $to = $this->reunistStr;
        
        $user = User::factory()->extraordinary_participant()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_extraordinary_member_becomes_honorary_member()
    {
        $from = $this->extraordinaryMemberStr; //convenience for replicating the tests
        $to = $this->honoraryMemberStr;
        
        $user = User::factory()->extraordinary_participant()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_extraordinary_member_becomes_member_of_merit()
    {
        $from = $this->extraordinaryMemberStr; //convenience for replicating the tests
        $to = $this->memberOfMeritStr;
        
        $user = User::factory()->extraordinary_participant()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_extraordinary_member_becomes_trainer()
    {
        $from = $this->extraordinaryMemberStr; //convenience for replicating the tests
        $to = $this->trainerStr;
        
        $user = User::factory()->extraordinary_participant()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_extraordinary_member_becomes_relationship()
    {
        $from = $this->extraordinaryMemberStr; //convenience for replicating the tests
        $to = $this->relationshipStr;
        
        $user = User::factory()->extraordinary_participant()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    /*
     * honorary member to ... tests
     */
    
    public function test_honorary_member_becomes_member()
    {
        $from = $this->honoraryMemberStr; //convenience for replicating the tests
        $to = $this->memberStr;
        
        $user = User::factory()->honorary_member()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_honorary_member_becomes_reunist()
    {
        $from = $this->honoraryMemberStr; //convenience for replicating the tests
        $to = $this->reunistStr;
        
        $user = User::factory()->honorary_member()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_honorary_member_becomes_extraordinary_member()
    {
        $from = $this->honoraryMemberStr; //convenience for replicating the tests
        $to = $this->extraordinaryMemberStr;
        
        $user = User::factory()->honorary_member()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_honorary_member_becomes_member_of_merit()
    {
        $from = $this->honoraryMemberStr; //convenience for replicating the tests
        $to = $this->memberOfMeritStr;
        
        $user = User::factory()->honorary_member()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_honorary_member_becomes_trainer()
    {
        $from = $this->honoraryMemberStr; //convenience for replicating the tests
        $to = $this->trainerStr;
        
        $user = User::factory()->honorary_member()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_honorary_member_becomes_relationship()
    {
        $from = $this->honoraryMemberStr; //convenience for replicating the tests
        $to = $this->relationshipStr;
        
        $user = User::factory()->honorary_member()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    /*
     * member of merit to ... tests
     */
    
    public function test_member_of_merit_becomes_member()
    {
        $from = $this->memberOfMeritStr; //convenience for replicating the tests
        $to = $this->memberStr;
        
        $user = User::factory()->member_of_merit()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_member_of_merit_becomes_reunist()
    {
        $from = $this->memberOfMeritStr; //convenience for replicating the tests
        $to = $this->reunistStr;
        
        $user = User::factory()->member_of_merit()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_member_of_merit_becomes_extraordinary_member()
    {
        $from = $this->memberOfMeritStr; //convenience for replicating the tests
        $to = $this->extraordinaryMemberStr;
        
        $user = User::factory()->member_of_merit()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_member_of_merit_becomes_honorary_member()
    {
        $from = $this->memberOfMeritStr; //convenience for replicating the tests
        $to = $this->honoraryMemberStr;
        
        $user = User::factory()->member_of_merit()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_member_of_merit_becomes_trainer()
    {
        $from = $this->memberOfMeritStr; //convenience for replicating the tests
        $to = $this->trainerStr;
        
        $user = User::factory()->member_of_merit()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_member_of_merit_becomes_relationship()
    {
        $from = $this->memberOfMeritStr; //convenience for replicating the tests
        $to = $this->relationshipStr;
        
        $user = User::factory()->member_of_merit()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    /*
     * Trainer to ... tests
     */
    
    public function test_trainer_becomes_member()
    {
        $from = $this->trainerStr; //convenience for replicating the tests
        $to = $this->memberStr;
        
        $user = User::factory()->trainer()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_trainer_becomes_reunist()
    {
        $from = $this->trainerStr; //convenience for replicating the tests
        $to = $this->reunistStr;
        
        $user = User::factory()->trainer()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_trainer_becomes_extraordinary_member()
    {
        $from = $this->trainerStr; //convenience for replicating the tests
        $to = $this->extraordinaryMemberStr;
        
        $user = User::factory()->trainer()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_trainer_becomes_honorary_member()
    {
        $from = $this->trainerStr; //convenience for replicating the tests
        $to = $this->honoraryMemberStr;
        
        $user = User::factory()->trainer()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_trainer_becomes_member_of_merit()
    {
        $from = $this->trainerStr; //convenience for replicating the tests
        $to = $this->memberOfMeritStr;
        
        $user = User::factory()->trainer()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_trainer_becomes_relationship()
    {
        $from = $this->trainerStr; //convenience for replicating the tests
        $to = $this->relationshipStr;
        
        $user = User::factory()->trainer()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    /*
     * Relationship to ... tests
     */
    
    public function test_relationship_becomes_member()
    {
        $from = $this->relationshipStr; //convenience for replicating the tests
        $to = $this->memberStr;
        
        $user = User::factory()->relationship()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_relationship_becomes_reunist()
    {
        $from = $this->relationshipStr; //convenience for replicating the tests
        $to = $this->reunistStr;
        
        $user = User::factory()->relationship()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_relationship_becomes_extraordinary_member()
    {
        $from = $this->relationshipStr; //convenience for replicating the tests
        $to = $this->extraordinaryMemberStr;
        
        $user = User::factory()->relationship()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_relationship_becomes_honorary_member()
    {
        $from = $this->relationshipStr; //convenience for replicating the tests
        $to = $this->honoraryMemberStr;
        
        $user = User::factory()->relationship()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_relationship_becomes_member_of_merit()
    {
        $from = $this->relationshipStr; //convenience for replicating the tests
        $to = $this->memberOfMeritStr;
        
        $user = User::factory()->relationship()->create();
        
        $this->test_log($user, $from, $to);
    }
    
    public function test_relationship_becomes_trainer()
    {
        $from = $this->relationshipStr; //convenience for replicating the tests
        $to = $this->trainerStr;
        
        $user = User::factory()->relationship()->create();
        
        $this->test_log($user, $from, $to);
    }
}
<?php

namespace Tests\Feature\UserEventLogEntry;

use App\Models\UserEventLogEntry;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

class DeleteEntryTest extends \TestCase
{
    use RefreshDatabase;
    
    private User $admin;
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach(Config::get('constants.Administrator'));
    }
    public function test_delete_entry()
    {
        $this->assertEquals(0, UserEventLogEntry::all()->count());
        
        $entry = UserEventLogEntry::factory()->create();
        
        $this->assertEquals(1, UserEventLogEntry::all()->count());
        
        $response = $this->actingAs($this->admin)->delete(route('user-event-log-entry.destroy', $entry));
        
        $response->assertRedirect(route('user-event-log-entry.index'));
        
        $this->assertEquals(0, UserEventLogEntry::all()->count());
    }
    
    public function test_delete_entry_of_many()
    {
        UserEventLogEntry::factory()->count(200)->create();
        $this->assertEquals(200, UserEventLogEntry::all()->count());
        
        $entry = UserEventLogEntry::all()->random();
        
        $response = $this->actingAs($this->admin)->delete(route('user-event-log-entry.destroy', $entry));
        
        $response->assertRedirect(route('user-event-log-entry.index'));
        
        $this->assertEquals(199, UserEventLogEntry::all()->count());
        $this->assertNull(UserEventLogEntry::find($entry->id));
    }
}

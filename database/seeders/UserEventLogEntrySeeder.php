<?php

namespace Database\Seeders;

use App\Enums\UserEventTypes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\User;

class UserEventLogEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        for ($i = 0; $i < 10; $i++)
        {
            $entry = new \App\Models\UserEventLogEntry();
            $entry->eventType = UserEventTypes::MemberTypeChanged;
            $entry->user_id = 1;
            $entry->eventDetails = "changed to reunist " . $i;
            $entry->save();
        }     
    }
}

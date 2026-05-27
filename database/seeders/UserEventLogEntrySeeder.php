<?php

namespace Database\Seeders;

use App\Enums\UserEventTypes;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserEventLogEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $nrOfUsers = User::count();
        \App\Models\UserEventLogEntry::factory()->randomUser($nrOfUsers)->count(10)->create();
    }
}

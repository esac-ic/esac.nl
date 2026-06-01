<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserEventLogEntry;
use Illuminate\Database\Seeder;

class UserEventLogEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $nrOfUsers = User::count();
        UserEventLogEntry::factory()->randomUser($nrOfUsers)->count(10)->create();
    }
}

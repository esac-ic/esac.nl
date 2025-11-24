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
        $nrOfUsers = User::count();
        \App\Models\UserEventLogEntry::factory()->randomUser($nrOfUsers)->count(10)->create();
    }
}

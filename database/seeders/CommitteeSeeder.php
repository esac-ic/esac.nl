<?php

namespace Database\Seeders;

use App\Models\Committee;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CommitteeSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->createCommitteeWithMembers();
        }
    }
    
    private function createCommitteeWithMembers(): void
    {
        $committee = Committee::factory()->hasMembers(5)->create();
        $committee->chair()->associate($committee->members()->first());
        Log::debug(json_encode($committee->chair()));
        $committee->save();
    }
}

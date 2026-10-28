<?php

namespace Database\Seeders;

use App\Models\Zekering;
use Illuminate\Database\Seeder;

class ZekeringenSeeder extends Seeder
{
    private const AMOUNT = 150;

    private const MAX_CHILDREN = 4;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $zekeringen = Zekering::factory()->count(self::AMOUNT)->create(['created_by' => 1]);

        foreach ($zekeringen as $zekering) {
            Zekering::factory()
                ->count(rand(0, self::MAX_CHILDREN))
                ->childOf($zekering)
                ->create(['created_by' => 1]);
        }
    }
}

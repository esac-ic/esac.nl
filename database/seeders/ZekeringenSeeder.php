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
        for ($i = 0; $i < self::AMOUNT; $i++) {
            $zekering = $this->createZekering(fake()->sentence(rand(6, 20)));

            $childCount = rand(0, self::MAX_CHILDREN);
            for ($j = 0; $j < $childCount; $j++) {
                $this->createZekering(fake()->sentence(rand(4, 12)), $zekering->id);
            }
        }
    }

    private function createZekering(string $text, ?int $parentId = null): Zekering
    {
        $zekering = new Zekering();
        $zekering->text = $text;
        $zekering->createdBy = 1;
        $zekering->score = 1;
        $zekering->parent_id = $parentId;
        $zekering->has_parent = $parentId !== null;
        $zekering->save();

        return $zekering;
    }
}

<?php

use Illuminate\Database\Seeder;

class frontendreplacementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $replacement = new \App\FrontEndReplacement();
        $replacement->word = "secretaris";
        $replacement->replacement = "Wouter Bles";
        $replacement->email = "secretaris@esac.nl";
        $replacement->save();

        $replacement = new \App\FrontEndReplacement();
        $replacement->word = "admin";
        $replacement->replacement = "lala land";
        $replacement->email = "admin@admin.nl";
        $replacement->save();

        $replacement = new \App\FrontEndReplacement();
        $replacement->word = "admin2";
        $replacement->replacement = "lalaland";
        $replacement->save();
    }
}

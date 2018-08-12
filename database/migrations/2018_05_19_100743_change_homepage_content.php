<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeHomepageContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menuItem = \App\MenuItem::query()
            ->where('urlName','=',\App\MenuItem::HOMEURL)
            ->first();
        if($menuItem != null){
            $content = $menuItem->content;
            $content->NL_text = "<h2 class=\"card-title\">Welkom</h2>
                <p class=\"card-text\">Welkom bij de ESAC, dé studenten klim- en bergsportvereniging van Eindhoven. Op onze website vind je meer informatie over wat de ESAC te bieden heeft. Heb je vragen? Neem dan vooral contact op met het bestuur.</p>

                <p class=\"card-text\">De Eindhovense Studenten Alpen Club houdt zich in de breedste zin bezig met de bergsport. Met veel plezier en gezelligheid genieten we van de hoogteverschillen in het landschap of in de hal. Hieronder is actuele informatie te vinden, informatie over lid worden of contact opnemen zijn elders op de website te vinden.</p>
              ";
            $content->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

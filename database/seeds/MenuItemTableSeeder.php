<?php

use Illuminate\Database\Seeder;

class MenuItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //homepage
        $text = new \App\Text(['NL_text' => 'Home', 'EN_text' => 'Home']);
        $text->save();
        $content = new \App\Text(['NL_text' => '<h2 class="card-title">Welkom</h2>
                <p class="card-text">Welkom bij de ESAC, dé studenten klim- en bergsportvereniging van Eindhoven. Op onze website vind je meer informatie over wat de ESAC te bieden heeft. Heb je vragen? Neem dan vooral contact op met het bestuur.</p>

                <p class="card-text">De Eindhovense Studenten Alpen Club houdt zich in de breedste zin bezig met de bergsport. Met veel plezier en gezelligheid genieten we van de hoogteverschillen in het landschap of in de hal. Hieronder is actuele informatie te vinden, informatie over lid worden of contact opnemen zijn elders op de website te vinden.</p>',
                'EN_text' => '<h2 class="card-title">Welcome</h2>
                <p class="card-text">Welkom bij de ESAC, dé studenten klim- en bergsportvereniging van Eindhoven. Op onze website vind je meer informatie over wat de ESAC te bieden heeft. Heb je vragen? Neem dan vooral contact op met het bestuur.</p>

                <p class="card-text">De Eindhovense Studenten Alpen Club houdt zich in de breedste zin bezig met de bergsport. Met veel plezier en gezelligheid genieten we van de hoogteverschillen in het landschap of in de hal. Hieronder is actuele informatie te vinden, informatie over lid worden of contact opnemen zijn elders op de website te vinden.</p>
                ']);
        $content->save();
        $menuItem = new \App\MenuItem();
        $menuItem->name= $text->id;
        $menuItem->urlName = \App\MenuItem::HOMEURL;
        $menuItem->login = false;
        $menuItem->deletable = false;
        $menuItem->editable = true;
        $menuItem->menuItem = true;
        $menuItem->content_id = $content->id;
        $menuItem->save();

        //agenda
        $text = new \App\Text(['NL_text' => 'Agenda', 'EN_text' => 'Agenda']);
        $text->save();
        $content = new \App\Text(['NL_text' => 'Agenda content', 'EN_text' => 'Agenda content']);
        $content->save();
        $menuItem = new \App\MenuItem();
        $menuItem->name= $text->id;
        $menuItem->urlName = \App\MenuItem::AGENDAURL;
        $menuItem->login = false;
        $menuItem->deletable = false;
        $menuItem->editable = true;
        $menuItem->menuItem = true;
        $menuItem->content_id = $content->id;
        $menuItem->save();

        //memberlist
        $text = new \App\Text(['NL_text' => 'Ledenlijst', 'EN_text' => 'Members list']);
        $text->save();
        $content = new \App\Text(['NL_text' => 'Ledenlijst content', 'EN_text' => 'Members list content']);
        $content->save();
        $menuItem = new \App\MenuItem();
        $menuItem->name= $text->id;
        $menuItem->urlName = \App\MenuItem::MEMBERLISTURL;
        $menuItem->login = true;
        $menuItem->deletable = false;
        $menuItem->editable = true;
        $menuItem->menuItem = true;
        $menuItem->content_id = $content->id;
        $menuItem->save();

        //news
        $text = new \App\Text(['NL_text' => 'Nieuws', 'EN_text' => 'News']);
        $text->save();
        $content = new \App\Text(['NL_text' => 'Nieuws content', 'EN_text' => 'News content']);
        $content->save();
        $menuItem = new \App\MenuItem();
        $menuItem->name= $text->id;
        $menuItem->urlName =\App\MenuItem::NEWSURL;
        $menuItem->login = false;
        $menuItem->deletable = false;
        $menuItem->editable = true;
        $menuItem->menuItem = true;
        $menuItem->content_id = $content->id;
        $menuItem->save();

        //subscribe
        $text = new \App\Text(['NL_text' => 'Meld je aan als lid', 'EN_text' => 'Subscribe as member']);
        $text->save();
        $content = new \App\Text(['NL_text' => 'Aanmelden content', 'EN_text' => 'Subscribe content']);
        $content->save();
        $menuItem = new \App\MenuItem();
        $menuItem->name= $text->id;
        $menuItem->urlName = \App\MenuItem::SUBSCRIBEURL;
        $menuItem->login = false;
        $menuItem->deletable = false;
        $menuItem->editable = true;
        $menuItem->menuItem = true;
        $menuItem->content_id = $content->id;
        $menuItem->save();
        
        //library
        $text = new \App\Text(['NL_text' => 'Bibliotheek', 'EN_text' => 'Library']);
        $text->save();
        $content = new \App\Text(['NL_text' => 'Bibliotheek content', 'EN_text' => 'Library content']);
        $content->save();
        $menuItem = new \App\MenuItem();
        $menuItem->name= $text->id;
        $menuItem->urlName =\App\MenuItem::LIBRARYURL;
        $menuItem->login = false;
        $menuItem->deletable = false;
        $menuItem->editable = true;
        $menuItem->menuItem = true;
        $menuItem->content_id = $content->id;
        $menuItem->save();

        //photos
        $text = new \App\Text(['NL_text' => 'Fotos', 'EN_text' => 'Photos']);
        $text->save();
        $content = new \App\Text(['NL_text' => 'Fotos content', 'EN_text' => 'Photos content']);
        $content->save();
        $menuItem = new \App\MenuItem();
        $menuItem->name= $text->id;
        $menuItem->urlName =\App\MenuItem::PHOTOURL;
        $menuItem->login = true;
        $menuItem->deletable = false;
        $menuItem->editable = true;
        $menuItem->menuItem = true;
        $menuItem->content_id = $content->id;
        $menuItem->save();

//        //Gallery
//        $text = new \App\Text(['NL_text' => 'Foto albums', 'EN_text' => 'Galleries']);
//        $text->save();
//        $content = new \App\Text(['NL_text' => 'Foto content', 'EN_text' => 'Foto content']);
//        $content->save();
//        $menuItem = new \App\MenuItem();
//        $menuItem->name= $text->id;
//        $menuItem->urlName = "photoGallery";
//        $menuItem->login = false;
//        $menuItem->deletable = false;
//        $menuItem->menuItem = true;
//        $menuItem->editable = true;
//        $menuItem->content_id = $content->id;
//        $menuItem->save();

        //zekeringen page page
        $text = new \App\Text(['NL_text' => 'Zekeringen', 'EN_text' => 'Zekeringen']);
        $text->save();
        $content = new \App\Text(['NL_text' => 'Zekeringen content', 'EN_text' => 'Zekeringen content']);
        $content->save();
        $menuItem = new \App\MenuItem();
        $menuItem->name= $text->id;
        $menuItem->urlName = \App\MenuItem::ZEKERINGURL;
        $menuItem->login = true;
        $menuItem->editable = true;
        $menuItem->deletable = false;
        $menuItem->menuItem = true;
        $menuItem->content_id = $content->id;
        $menuItem->save();
    }
}
<?php

namespace Database\Seeders;

use App\Models\MenuItem;
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
        $content = '<h2 class="card-title">Welcome</h2>
                <p class="card-text">Welkom bij de ESAC, dé studenten klim- en bergsportvereniging van Eindhoven. Op onze website vind je meer informatie over wat de ESAC te bieden heeft. Heb je vragen? Neem dan vooral contact op met het bestuur.</p>

                <p class="card-text">De Eindhovense Studenten Alpen Club houdt zich in de breedste zin bezig met de bergsport. Met veel plezier en gezelligheid genieten we van de hoogteverschillen in het landschap of in de hal. Hieronder is actuele informatie te vinden, informatie over lid worden of contact opnemen zijn elders op de website te vinden.</p>';
        $menuItem = new MenuItem();
        $menuItem->name = 'Home';
        $menuItem->urlName = MenuItem::HOMEURL;
        $menuItem->login = false;
        $menuItem->deletable = false;
        $menuItem->editable = true;
        $menuItem->menuItem = true;
        $menuItem->content = $content;
        $menuItem->save();

        //agenda
        $menuItem = new MenuItem();
        $menuItem->name = 'Agenda';
        $menuItem->urlName = MenuItem::AGENDAURL;
        $menuItem->login = false;
        $menuItem->deletable = false;
        $menuItem->editable = true;
        $menuItem->menuItem = true;
        $menuItem->content = 'Agenda content';
        $menuItem->save();

        //memberlist
        $menuItem = new MenuItem();
        $menuItem->name = 'Members list';
        $menuItem->urlName = MenuItem::MEMBERLISTURL;
        $menuItem->login = true;
        $menuItem->deletable = false;
        $menuItem->editable = true;
        $menuItem->menuItem = true;
        $menuItem->content = 'Members list content';
        $menuItem->save();

        //news
        $menuItem = new MenuItem();
        $menuItem->name = 'News';
        $menuItem->urlName = MenuItem::NEWSURL;
        $menuItem->login = false;
        $menuItem->deletable = false;
        $menuItem->editable = true;
        $menuItem->menuItem = true;
        $menuItem->content = 'News content';
        $menuItem->save();

        //subscribe
        $menuItem = new MenuItem();
        $menuItem->name = 'Subscribe as member';
        $menuItem->urlName = MenuItem::SUBSCRIBEURL;
        $menuItem->login = false;
        $menuItem->deletable = false;
        $menuItem->editable = true;
        $menuItem->menuItem = true;
        $menuItem->content = 'Subscribe content';
        $menuItem->save();

        //library
        $menuItem = new MenuItem();
        $menuItem->name = 'Library';
        $menuItem->urlName = MenuItem::LIBRARYURL;
        $menuItem->login = false;
        $menuItem->deletable = false;
        $menuItem->editable = true;
        $menuItem->menuItem = true;
        $menuItem->content = 'Library content';
        $menuItem->save();

        //zekeringen page
        $menuItem = new MenuItem();
        $menuItem->name = 'Zekeringen';
        $menuItem->urlName = MenuItem::ZEKERINGURL;
        $menuItem->login = true;
        $menuItem->editable = true;
        $menuItem->deletable = false;
        $menuItem->menuItem = true;
        $menuItem->content = 'Zekeringen content';
        $menuItem->save();
    }
}

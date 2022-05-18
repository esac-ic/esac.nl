<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NewsItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $title = new \App\Text(['NL_text' => 'Nieuwe website!', 'EN_text' => 'New website esac!']);
        $title->save();
        $text = new \App\Text(['NL_text' => 'Sommigen van jullie hebben misschien al geruchten gehoord, maar het is echt waar: in 2017 komt er een gloednieuwe ESAC-website. Allereerst veel dank voor de internetcommissie, die hard bezig is om dit mogelijk te maken! Iedereen die graag updates over de nieuwe website wil ontvangen, kan zich via een e-mail naar de IC aanmelden voor de maillijst. Als je nog goede ideeën hebt, vertel deze dan snel aan de IC; hoe verder het ontwikkelingsproces gevorderd is, hoe lastiger het wordt om nieuwe ideeën te implementeren.', 'EN_text' => 'Some of you may have heard rumors, but it is true: in 2017, there will be a brand new ESAC website. First of all, thank you for the internet commission, who is working hard to make this happen! Anyone who would like to receive updates about the new website can log in to the IC by email to the IC. If you have any good ideas, please tell them the IC quickly; The further the development process is advanced, the more difficult it becomes to implement new ideas.']);
        $text->save();

        $newsItem = new \App\NewsItem();
        $newsItem->title = $title->id;
        $newsItem->text = $text->id;
        $newsItem->author = "Piet jansen";
        $newsItem->save();

        $title = new \App\Text(['NL_text' => 'Korting monk!', 'EN_text' => 'Discount Monk!']);
        $title->save();
        $text = new \App\Text(['NL_text' => 'Goed nieuws! Vanaf 1 mei kunnen alle ESAC-leden op zondagavond op Monk klokken op 6.00 euro. De korting begint vanaf 18:00 op zondag en is van toepassing op iedereen, dus zelfs als u geen sportkaart hebt, krijgt u korting.Op dit moment is het gebaseerd op goed vertrouwen, als je Monk weet dat je een ESAC lid bent, krijg je de korting. Als de korting succesvol lijkt te zijn, zullen we iets anders opleveren om ESAC-leden bij Monk te identificeren.', 'EN_text' => 'Good news! From May the 1st onward, all ESAC members can boulder at Monk on Sunday evening for 6.50 euros. The discount starts from 18:00 on Sundays and applies to everyone, so even if you do not have a sports card you get a discount. At the moment, it is based on good faith, if you let Monk know that you are an ESAC member, you get the discount. If the discount appears to be successful, we will come up with something else to identify ESAC members at Monk.']);
        $text->save();

        $newsItem = new \App\NewsItem();
        $newsItem->title = $title->id;
        $newsItem->text = $text->id;
        $newsItem->author = "Klaas jansen";
        $newsItem->save();
    }
}

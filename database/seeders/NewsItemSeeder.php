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
        $newsItem = new \App\NewsItem();
        $newsItem->title = 'New website esac!';
        $newsItem->text = 'Some of you may have heard rumors, but it is true: in 2017, there will be a brand new ESAC website. First of all, thank you for the internet commission, who is working hard to make this happen! Anyone who would like to receive updates about the new website can log in to the IC by email to the IC. If you have any good ideas, please tell them the IC quickly; The further the development process is advanced, the more difficult it becomes to implement new ideas.';
        $newsItem->author = "Piet jansen";
        $newsItem->save();

        $newsItem = new \App\NewsItem();
        $newsItem->title = 'Discount Monk!';
        $newsItem->text = 'Good news! From May the 1st onward, all ESAC members can boulder at Monk on Sunday evening for 6.50 euros. The discount starts from 18:00 on Sundays and applies to everyone, so even if you do not have a sports card you get a discount. At the moment, it is based on good faith, if you let Monk know that you are an ESAC member, you get the discount. If the discount appears to be successful, we will come up with something else to identify ESAC members at Monk.';
        $newsItem->author = "Klaas jansen";
        $newsItem->save();
    }
}

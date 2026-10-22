<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

/**
 * The pages the site's own routes show content from.
 */
class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [MenuItem::HOMEURL, 'Home', false, '<h2 class="card-title">Welcome</h2><p class="card-text">Welcome to the ESAC, the student climbing and mountaineering club of Eindhoven.</p>'],
            [MenuItem::AGENDAURL, 'Agenda', false, 'Agenda content'],
            [MenuItem::MEMBERLISTURL, 'Members list', true, 'Members list content'],
            [MenuItem::NEWSURL, 'News', false, 'News content'],
            [MenuItem::SUBSCRIBEURL, 'Subscribe as member', false, 'Subscribe content'],
            [MenuItem::LIBRARYURL, 'Library', false, 'Library content'],
            [MenuItem::ZEKERINGURL, 'Zekeringen', true, 'Zekeringen content'],
        ];

        foreach ($pages as $index => [$urlName, $name, $login, $content]) {
            MenuItem::query()->forceCreate([
                'position' => ($index + 1) * 10,
                'url_name' => $urlName,
                'name' => $name,
                'login' => $login,
                'content' => $content,
                'menu_item' => true,
                'deletable' => false,
                'editable' => true,
            ]);
        }
    }
}

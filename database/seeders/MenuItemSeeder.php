<?php

namespace Database\Seeders;

use App\Enums\PageSlug;
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
            [PageSlug::Home, 'Home', false, '<h2 class="card-title">Welcome</h2><p class="card-text">Welcome to the ESAC, the student climbing and mountaineering club of Eindhoven.</p>'],
            [PageSlug::Events, 'Events', false, 'Events content'],
            [PageSlug::Members, 'Members list', true, 'Members list content'],
            [PageSlug::News, 'News', false, 'News content'],
            [PageSlug::SignUp, 'Become a member', false, 'Sign-up content'],
            [PageSlug::Library, 'Library', false, 'Library content'],
            [PageSlug::Zekeringen, 'Zekeringen', true, 'Zekeringen content'],
        ];

        foreach ($pages as $index => [$slug, $name, $requiresLogin, $content]) {
            MenuItem::query()->forceCreate([
                'position' => ($index + 1) * 10,
                'slug' => $slug->value,
                'name' => $name,
                'requires_login' => $requiresLogin,
                'content' => $content,
                'show_in_menu' => true,
                'deletable' => false,
                'editable' => true,
            ]);
        }
    }
}

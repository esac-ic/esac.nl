<?php

namespace Database\Seeders;

use App\Enums\PageSlug;
use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * The pages the site's own routes show content from.
 */
class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [PageSlug::Home, 'Home', false, '<h2 class="card-title">Welcome</h2><p class="card-text">Welcome to the ESAC, the student climbing and mountaineering club of Eindhoven.</p>'],
            [PageSlug::Events, 'Events', false, '<p>Climbing weekends, training evenings and everything in between. Register for an event from its page.</p>'],
            [PageSlug::Members, 'Members list', true, '<p>All current members, so you can find each other for a climbing trip.</p>'],
            [PageSlug::News, 'News', false, '<p>The latest news from the association.</p>'],
            [PageSlug::SignUp, 'Become a member', false, '<p>Fill in the form below to become a member. The board approves your sign-up within a few days.</p>'],
            [PageSlug::Library, 'Library', false, '<p>Guidebooks and maps you can borrow from the association.</p>'],
            [PageSlug::Zekeringen, 'Zekeringen', true, '<p>Funny quotes overheard while belaying, added by members.</p>'],
        ];

        foreach ($pages as $index => [$slug, $name, $requiresLogin, $body]) {
            Page::query()->forceCreate([
                'position' => $index + 1,
                'slug' => $slug->value,
                'name' => $name,
                'requires_login' => $requiresLogin,
                'body' => $body,
                'show_in_menu' => true,
                'deletable' => false,
                'editable' => true,
            ]);
        }
    }
}

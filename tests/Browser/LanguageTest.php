<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LanguageTest extends DuskTestCase
{
    /**
     * A Dusk test to test website translation.
     *
     * @return void
     */
    public function test_translation()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    ->visit('/home')
                    ->assertSee('Welkom')
                    ->click('#selected_lang')
                    ->click('#other_lang')
                    ->visit('/home')
                    ->assertSee('Welcome');
        });
    }
}

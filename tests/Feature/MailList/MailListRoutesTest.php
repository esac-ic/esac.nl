<?php

namespace Tests\Feature\MailList;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MailListRoutesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function mailing_lists_can_not_be_edited()
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Config::get('constants.Administrator'));

        $this->actingAs($admin)->get('mailList/leden.esac.nl/edit')->assertNotFound();
        $this->actingAs($admin)->patch('mailList/leden.esac.nl')->assertMethodNotAllowed();
    }
}

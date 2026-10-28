<?php

namespace Tests\Unit\Mailman;

use App\Services\Mailman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GetAllMailListsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('services.mailman.url', 'http://mailman.test/3.1');
    }

    public function test_get_multiple_mail_lists()
    {
        Http::fake([
            'http://mailman.test/3.1/lists' => Http::response([
                'start' => 0,
                'total_size' => 4,
                'entries' => [
                    [
                        'advertised' => 1,
                        'display_name' => 'Alle-leden',
                        'fqdn_listname' => 'alle-leden@esac.nl',
                        'list_id' => 'alle-leden.esac.nl',
                        'list_name' => 'alle-leden',
                        'mail_host' => 'esac.nl',
                        'member_count' => 1,
                        'volume' => 1,
                        'description' => '',
                        'self_link' => 'http://localhost:8001/3.1/lists/alle-leden.esac.nl',
                        'http_etag' => 'e2b12954bea5c54eb1a71bd017b70593b392b5f6',
                    ],
                    [
                        'advertised' => 1,
                        'display_name' => 'Lid',
                        'fqdn_listname' => 'lid@esac.nl',
                        'list_id' => 'lid.esac.nl',
                        'list_name' => 'lid',
                        'mail_host' => 'esac.nl',
                        'member_count' => 5,
                        'volume' => 1,
                        'description' => '',
                        'self_link' => 'http://localhost:8001/3.1/lists/lid.esac.nl',
                        'http_etag' => '07bdc22d9deccfad6c5f97f4d985088774e851c2',
                    ],
                    [
                        'advertised' => 1,
                        'display_name' => 'Nieuwsbrief',
                        'fqdn_listname' => 'nieuwsbrief@esac.nl',
                        'list_id' => 'nieuwsbrief.esac.nl',
                        'list_name' => 'nieuwsbrief',
                        'mail_host' => 'esac.nl',
                        'member_count' => 48,
                        'volume' => 1,
                        'description' => '',
                        'self_link' => 'http://localhost:8001/3.1/lists/nieuwsbrief.esac.nl',
                        'http_etag' => 'afed00406729829674a0f3ba273a603bdefd0e62',
                    ],
                    [
                        'advertised' => 1,
                        'display_name' => 'Reunist',
                        'fqdn_listname' => 'reunist@esac.nl',
                        'list_id' => 'reunist.esac.nl',
                        'list_name' => 'reunist',
                        'mail_host' => 'esac.nl',
                        'member_count' => 0,
                        'volume' => 1,
                        'description' => '',
                        'self_link' => 'http://localhost:8001/3.1/lists/reunist.esac.nl',
                        'http_etag' => 'f699a1581810979fdace815214e1963933795174',
                    ],
                ],
                'http_etag' => '9573fac7ddf920034d94e469e4232c22fc8dd4ad',
            ]),
        ]);

        // make the object via the service container
        $mailman = $this->app->make(Mailman::class);

        $response = $mailman->getAllMailLists();

        $this->assertCount(4, $response);

        $this->assertEquals('alle-leden.esac.nl', $response[0]->id);
        $this->assertEquals('Alle-leden', $response[0]->name);
        $this->assertEquals('alle-leden@esac.nl', $response[0]->address);
        $this->assertEquals(1, $response[0]->members_count);
        $this->assertEquals([], $response[0]->members);

        $this->assertEquals('lid.esac.nl', $response[1]->id);
        $this->assertEquals('Lid', $response[1]->name);
        $this->assertEquals('lid@esac.nl', $response[1]->address);
        $this->assertEquals(5, $response[1]->members_count);
        $this->assertEquals([], $response[1]->members);

        $this->assertEquals('nieuwsbrief.esac.nl', $response[2]->id);
        $this->assertEquals('Nieuwsbrief', $response[2]->name);
        $this->assertEquals('nieuwsbrief@esac.nl', $response[2]->address);
        $this->assertEquals(48, $response[2]->members_count);
        $this->assertEquals([], $response[2]->members);

        $this->assertEquals('reunist.esac.nl', $response[3]->id);
        $this->assertEquals('Reunist', $response[3]->name);
        $this->assertEquals('reunist@esac.nl', $response[3]->address);
        $this->assertEquals(0, $response[3]->members_count);
        $this->assertEquals([], $response[3]->members);
    }

    public function test_no_mail_lists()
    {
        Http::fake([
            'http://mailman.test/3.1/lists' => Http::response([
                'start' => 0,
                'total_size' => 0,
                'http_etag' => '9573fac7ddf920034d94e469e4232c22fc8dd4ad',
            ]),
        ]);

        // make the object via the service container
        $mailman = $this->app->make(Mailman::class);

        $response = $mailman->getAllMailLists();

        $this->assertCount(0, $response);
        $this->assertEquals(collect(), $response);
    }
}

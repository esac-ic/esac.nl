<?php

namespace Tests\Unit\MailListFacade;

use App\CustomClasses\MailList\MailListFacade;
use App\CustomClasses\MailList\MailListParser;
use App\CustomClasses\MailList\MailMan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\MailManager;
use Illuminate\Support\Facades\Log;
use Mockery\MockInterface;

class GetAllMailListsTest extends \TestCase
{
    use RefreshDatabase;
    
    public function test_get_multiple_mail_lists()
    {
        //mock so we don't actually send requests to the mailman API
        $this->mock(MailMan::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')
                ->with('/lists')
                ->once()
                ->andReturn(collect([
                    'start' => 0,
                    'total_size' => 4,
                    'entries' => [
                        [
                            "advertised" => 1,
                            "display_name" => "Alle-leden",
                            "fqdn_listname" => "alle-leden@esac.nl",
                            "list_id" => "alle-leden.esac.nl",
                            "list_name" => "alle-leden",
                            "mail_host" => "esac.nl",
                            "member_count" => 1,
                            "volume" => 1,
                            "description" => "",
                            "self_link" => "http://localhost:8001/3.1/lists/alle-leden.esac.nl",
                            "http_etag" => "e2b12954bea5c54eb1a71bd017b70593b392b5f6",
                        ],
                        [
                            "advertised" => 1,
                            "display_name" => "Lid",
                            "fqdn_listname" => "lid@esac.nl",
                            "list_id" => "lid.esac.nl",
                            "list_name" => "lid",
                            "mail_host" => "esac.nl",
                            "member_count" => 5,
                            "volume" => 1,
                            "description" => "",
                            "self_link" => "http://localhost:8001/3.1/lists/lid.esac.nl",
                            "http_etag" => "07bdc22d9deccfad6c5f97f4d985088774e851c2",
                        ],
                        [
                            "advertised" => 1,
                            "display_name" => "Nieuwsbrief",
                            "fqdn_listname" => "nieuwsbrief@esac.nl",
                            "list_id" => "nieuwsbrief.esac.nl",
                            "list_name" => "nieuwsbrief",
                            "mail_host" => "esac.nl",
                            "member_count" => 48,
                            "volume" => 1,
                            "description" => "",
                            "self_link" => "http://localhost:8001/3.1/lists/nieuwsbrief.esac.nl",
                            "http_etag" => "afed00406729829674a0f3ba273a603bdefd0e62",
                        ],
                        [
                            "advertised" => 1,
                            "display_name" => "Reunist",
                            "fqdn_listname" => "reunist@esac.nl",
                            "list_id" => "reunist.esac.nl",
                            "list_name" => "reunist",
                            "mail_host" => "esac.nl",
                            "member_count" => 0,
                            "volume" => 1,
                            "description" => "",
                            "self_link" => "http://localhost:8001/3.1/lists/reunist.esac.nl",
                            "http_etag" => "f699a1581810979fdace815214e1963933795174",
                        ],
                    ],
                    'http_etag' => "9573fac7ddf920034d94e469e4232c22fc8dd4ad"
                ]));
        });
        
        //make the object via the service container
        $facade = $this->app->make(MailListFacade::class);
        
        $response = $facade->getAllMailLists();
        
        
        $this->assertCount(4, $response);
        
        $this->assertEquals("alle-leden.esac.nl",   $response[0]->getId());
        $this->assertEquals("Alle-leden",           $response[0]->getName());
        $this->assertEquals("alle-leden@esac.nl",   $response[0]->getAddress());
        $this->assertEquals(1,                      $response[0]->getMembersCount());
        $this->assertEquals(array(),                         $response[0]->getMembers());
        
        $this->assertEquals("lid.esac.nl",  $response[1]->getId());
        $this->assertEquals("Lid",          $response[1]->getName());
        $this->assertEquals("lid@esac.nl",  $response[1]->getAddress());
        $this->assertEquals(5,              $response[1]->getMembersCount());
        $this->assertEquals(array(),                 $response[1]->getMembers());
        
        $this->assertEquals("nieuwsbrief.esac.nl",  $response[2]->getId());
        $this->assertEquals("Nieuwsbrief",          $response[2]->getName());
        $this->assertEquals("nieuwsbrief@esac.nl",  $response[2]->getAddress());
        $this->assertEquals(48,                     $response[2]->getMembersCount());
        $this->assertEquals(array(),                         $response[2]->getMembers());
        
        $this->assertEquals("reunist.esac.nl",  $response[3]->getId());
        $this->assertEquals("Reunist",          $response[3]->getName());
        $this->assertEquals("reunist@esac.nl",  $response[3]->getAddress());
        $this->assertEquals(0,                  $response[3]->getMembersCount());
        $this->assertEquals(array(),                     $response[3]->getMembers());
    }
    
    public function test_no_mail_lists()
    {
        //mock so we don't actually send requests to the mailman API
        $this->mock(MailMan::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')
                ->with('/lists')
                ->once()
                ->andReturn(collect([
                    'start' => 0,
                    'total_size' => 0,
                    'http_etag' => "9573fac7ddf920034d94e469e4232c22fc8dd4ad"
                ]));
        });
        
        //make the object via the service container
        $facade = $this->app->make(MailListFacade::class);
        
        $response = $facade->getAllMailLists();
        
        
        $this->assertCount(0, $response);
        $this->assertEquals(collect(), $response);
    }
}

<?php

namespace Tests\Feature;

use App\CustomClasses\MailList\MailListFacade;
use App\CustomClasses\MailList\MailMan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

class GetAllMailListIdsTest extends \TestCase
{
    use RefreshDatabase;
    
    public function test_no_mail_lists()
    {
        $this->mock(MailMan::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')
                ->once()
                ->with('/lists')
                ->andReturn(json_decode(json_encode([
                    "start" => 0,
                    "total_size" => 0,
                    "http_etag" => "32223434a0f3af4cdc4673d1fbc5bac1f6d98fd3"
                ])));
        });
        
        $facade = $this->app->make(MailListFacade::class);
        
        $response = $facade->getAllMailListIds();
        
        $this->assertEmpty($response);
    }
    
    public function test_single_mail_list()
    {
        $this->mock(MailMan::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')
                ->once()
                ->with('/lists')
                ->andReturn(json_decode(json_encode([
                    "start" => 0,
                    "total_size" => 1,
                    "entries" => [
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
                        ]
                    ],
                    "http_etag" => "32223434a0f3af4cdc4673d1fbc5bac1f6d98fd3"
                ])));
        });
        
        $facade = $this->app->make(MailListFacade::class);
        
        $response = $facade->getAllMailListIds();
        
        $this->assertCount(1, $response);
        $this->assertEquals("alle-leden.esac.nl", $response[0]);
    }
    
    public function test_multiple_mail_lists()
    {
        $this->mock(MailMan::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')
                ->once()
                ->with('/lists')
                ->andReturn(json_decode(json_encode([
                    "start" => 0,
                    "total_size" => 4,
                    "entries" => [
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
                            "member_count" => 69,
                            "volume" => 1,
                            "description" => "Some kind of description",
                            "self_link" => "http://localhost:8001/3.1/lists/lid.esac.nl",
                            "http_etag" => "07bdc22d9deccfad6c5f97f4d985088774e851c2",
                        ],
                        [
                            "advertised" => 1,
                            "display_name" => "Reunist",
                            "fqdn_listname" => "reunist@esac.nl",
                            "list_id" => "reunist.esac.nl",
                            "list_name" => "reunist",
                            "mail_host" => "esac.nl",
                            "member_count" => 420,
                            "volume" => 1,
                            "description" => "",
                            "self_link" => "http://localhost:8001/3.1/lists/reunist.esac.nl",
                            "http_etag" => "f699a1581810979fdace815214e1963933795174",
                        ],
                    ],
                    "http_etag" => "32223434a0f3af4cdc4673d1fbc5bac1f6d98fd3"
                ])));
        });
        
        $facade = $this->app->make(MailListFacade::class);
        
        $response = $facade->getAllMailListIds();
        
        $this->assertCount(3, $response);
        $this->assertEquals("alle-leden.esac.nl", $response[0]);
        $this->assertEquals("lid.esac.nl", $response[1]);
        $this->assertEquals("reunist.esac.nl", $response[2]);
    }
}

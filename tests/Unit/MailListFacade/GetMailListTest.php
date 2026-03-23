<?php

namespace Tests\Unit\MailListFacade;

use App\CustomClasses\MailList\MailListFacade;
use App\CustomClasses\MailList\MailMan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;
use TestCase;

class GetMailListTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Config::set('mailman.url', 'http://localhost:8001/3.1');
    }

    public function test_happy_flow_multiple_members()
    {
        $this->mock(MailMan::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')
                ->with('/lists/lid.esac.nl')
                ->once()
                ->andReturn(collect([
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
                ]));
            
            $mock->shouldReceive('get')
                ->with('/lists/lid.esac.nl/roster/member')
                ->once()
                ->andReturn(collect([
                    "start" => 0,
                    "total_size" => 5,
                    "entries" => [
                        [
                            "address" => "http://localhost:8001/3.1/addresses/agustinavandekeebler@yahoo.com",
                            "bounce_score" => 0,
                            "last_warning_sent" => "0001-01-01T00:00:00",
                            "total_warnings_sent" => 0,
                            "delivery_mode" => "regular",
                            "email" => "agustinavandekeebler@yahoo.com",
                            "list_id" => "lid.esac.nl",
                            "subscription_mode" => "as_address",
                            "role" => "member",
                            "user" => "http://localhost:8001/3.1/users/2dc4deeb915a4be9b7fb3a74e50c3c00",
                            "display_name" => "Agustina van de Keebler",
                            "self_link" => "http://localhost:8001/3.1/members/b7b995cbbca24f89bc3bff15bdd2fc1f",
                            "member_id" => "b7b995cbbca24f89bc3bff15bdd2fc1f",
                            "http_etag" => "198400958781eb093955a3db4bbde39ebfa2c7b0",
                        ],
                        [
                            "address" => "http://localhost:8001/3.1/addresses/aidenvandebergstrom@hotmail.com",
                            "bounce_score" => 0,
                            "last_warning_sent" => "0001-01-01T00:00:00",
                            "total_warnings_sent" => 0,
                            "delivery_mode" => "regular",
                            "email" => "aidenvandebergstrom@hotmail.com",
                            "list_id" => "lid.esac.nl",
                            "subscription_mode" => "as_address",
                            "role" => "member",
                            "user" => "http://localhost:8001/3.1/users/0f357a1637924d8dadef95c4199274cd",
                            "display_name" => "Aiden van de Bergstrom",
                            "self_link" => "http://localhost:8001/3.1/members/0f357a1637924d8dadef95c4199274cd",
                            "member_id" => "0f357a1637924d8dadef95c4199274cd",
                            "http_etag" => "198400958781eb093955a3db4bbde39ebfa2c7b0",
                        ],
                        [
                            "address" => "http://localhost:8001/3.1/addresses/antonettahaley@hotmail.com",
                            "bounce_score" => 0,
                            "last_warning_sent" => "0001-01-01T00:00:00",
                            "total_warnings_sent" => 0,
                            "delivery_mode" => "regular",
                            "email" => "antonettahaley@hotmail.com",
                            "list_id" => "lid.esac.nl",
                            "subscription_mode" => "as_address",
                            "role" => "member",
                            "user" => "http://localhost:8001/3.1/users/1deb8d49c2944cdfb93ffa29748378a4",
                            "display_name" => "Antonetta Haley",
                            "self_link" => "http://localhost:8001/3.1/members/1deb8d49c2944cdfb93ffa29748378a4",
                            "member_id" => "1deb8d49c2944cdfb93ffa29748378a4",
                            "http_etag" => "198400958781eb093955a3db4bbde39ebfa2c7b0",
                        ],
                        [
                            "address" => "http://localhost:8001/3.1/addresses/camdendekovacek@gmail.com",
                            "bounce_score" => 0,
                            "last_warning_sent" => "0001-01-01T00:00:00",
                            "total_warnings_sent" => 0,
                            "delivery_mode" => "regular",
                            "email" => "camdendekovacek@gmail.com",
                            "list_id" => "lid.esac.nl",
                            "subscription_mode" => "as_address",
                            "role" => "member",
                            "user" => "http://localhost:8001/3.1/users/10b18072878f4b98a080696432c3833c",
                            "display_name" => "Camden de Kovacek",
                            "self_link" => "http://localhost:8001/3.1/members/10b18072878f4b98a080696432c3833c",
                            "member_id" => "10b18072878f4b98a080696432c3833c",
                            "http_etag" => "198400958781eb093955a3db4bbde39ebfa2c7b0",
                        ],
                        [
                            "address" => "http://localhost:8001/3.1/addresses/darianavandeckow@gmail.com",
                            "bounce_score" => 0,
                            "last_warning_sent" => "0001-01-01T00:00:00",
                            "total_warnings_sent" => 0,
                            "delivery_mode" => "regular",
                            "email" => "darianavandeckow@gmail.com",
                            "list_id" => "lid.esac.nl",
                            "subscription_mode" => "as_address",
                            "role" => "member",
                            "user" => "http://localhost:8001/3.1/users/085e7b8f3e6740e2a8a09fc03d6115f9",
                            "display_name" => "Dariana van Deckow",
                            "self_link" => "http://localhost:8001/3.1/members/085e7b8f3e6740e2a8a09fc03d6115f9",
                            "member_id" => "085e7b8f3e6740e2a8a09fc03d6115f9",
                            "http_etag" => "198400958781eb093955a3db4bbde39ebfa2c7b0",
                        ],
                    ],
                    "http_etag" => "32223434a0f3af4cdc4673d1fbc5bac1f6d98fd3",
                ]));
        });
        
        $facade = $this->app->make(MailListFacade::class);
        
        $response = $facade->getMailList("lid.esac.nl");
        
        $this->assertNotNull($response);
        
        $this->assertEquals("lid.esac.nl",  $response->getId());
        $this->assertEquals("lid@esac.nl",  $response->getAddress());
        $this->assertEquals(5,              $response->getMembersCount());
        $this->assertEquals("Lid",          $response->getName());
        $this->assertCount(5,           $response->getMembers());
        
        //test correct member parsing
        $this->assertEquals("Agustina van de Keebler",  $response->getMembers()[0]->getName());
        $this->assertEquals("Aiden van de Bergstrom",   $response->getMembers()[1]->getName());
        $this->assertEquals("Antonetta Haley",          $response->getMembers()[2]->getName());
        $this->assertEquals("Camden de Kovacek",        $response->getMembers()[3]->getName());
        $this->assertEquals("Dariana van Deckow",       $response->getMembers()[4]->getName());
        
        $this->assertEquals("agustinavandekeebler@yahoo.com",   $response->getMembers()[0]->getAddress());
        $this->assertEquals("aidenvandebergstrom@hotmail.com",  $response->getMembers()[1]->getAddress());
        $this->assertEquals("antonettahaley@hotmail.com",       $response->getMembers()[2]->getAddress());
        $this->assertEquals("camdendekovacek@gmail.com",        $response->getMembers()[3]->getAddress());
        $this->assertEquals("darianavandeckow@gmail.com",       $response->getMembers()[4]->getAddress());
        
    }
    
    public function test_happy_flow_single_member()
    {
        $this->mock(MailMan::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')
                ->with('/lists/lid.esac.nl')
                ->once()
                ->andReturn(collect([
                    "advertised" => 1,
                    "display_name" => "Lid",
                    "fqdn_listname" => "lid@esac.nl",
                    "list_id" => "lid.esac.nl",
                    "list_name" => "lid",
                    "mail_host" => "esac.nl",
                    "member_count" => 1,
                    "volume" => 1,
                    "description" => "",
                    "self_link" => "http://localhost:8001/3.1/lists/lid.esac.nl",
                    "http_etag" => "07bdc22d9deccfad6c5f97f4d985088774e851c2",
                ]));
            
            $mock->shouldReceive('get')
                ->with('/lists/lid.esac.nl/roster/member')
                ->once()
                ->andReturn(collect([
                    "start" => 0,
                    "total_size" => 1,
                    "entries" => [
                        [
                            "address" => "http://localhost:8001/3.1/addresses/agustinavandekeebler@yahoo.com",
                            "bounce_score" => 0,
                            "last_warning_sent" => "0001-01-01T00:00:00",
                            "total_warnings_sent" => 0,
                            "delivery_mode" => "regular",
                            "email" => "agustinavandekeebler@yahoo.com",
                            "list_id" => "lid.esac.nl",
                            "subscription_mode" => "as_address",
                            "role" => "member",
                            "user" => "http://localhost:8001/3.1/users/2dc4deeb915a4be9b7fb3a74e50c3c00",
                            "display_name" => "Agustina van de Keebler",
                            "self_link" => "http://localhost:8001/3.1/members/b7b995cbbca24f89bc3bff15bdd2fc1f",
                            "member_id" => "b7b995cbbca24f89bc3bff15bdd2fc1f",
                            "http_etag" => "198400958781eb093955a3db4bbde39ebfa2c7b0",
                        ],
                    ],
                    "http_etag" => "32223434a0f3af4cdc4673d1fbc5bac1f6d98fd3",
                ]));
        });
        
        $facade = $this->app->make(MailListFacade::class);
        
        $response = $facade->getMailList("lid.esac.nl");
        
        $this->assertNotNull($response);
        
        $this->assertEquals("lid.esac.nl",  $response->getId());
        $this->assertEquals("lid@esac.nl",  $response->getAddress());
        $this->assertEquals(1,              $response->getMembersCount());
        $this->assertEquals("Lid",          $response->getName());
        $this->assertCount(1,           $response->getMembers());
        
        //test correct member parsing
        $this->assertEquals("Agustina van de Keebler",  $response->getMembers()[0]->getName());
        $this->assertEquals("agustinavandekeebler@yahoo.com",   $response->getMembers()[0]->getAddress());
    }
    
    public function test_happy_flow_no_members()
    {
        $this->mock(MailMan::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')
                ->with('/lists/lid.esac.nl')
                ->once()
                ->andReturn(collect([
                    "advertised" => 1,
                    "display_name" => "Lid",
                    "fqdn_listname" => "lid@esac.nl",
                    "list_id" => "lid.esac.nl",
                    "list_name" => "lid",
                    "mail_host" => "esac.nl",
                    "member_count" => 0,
                    "volume" => 1,
                    "description" => "",
                    "self_link" => "http://localhost:8001/3.1/lists/lid.esac.nl",
                    "http_etag" => "07bdc22d9deccfad6c5f97f4d985088774e851c2",
                ]));
            
            $mock->shouldReceive('get')
                ->with('/lists/lid.esac.nl/roster/member')
                ->once()
                ->andReturn(collect([
                    "start" => 0,
                    "total_size" => 0,
                    "http_etag" => "32223434a0f3af4cdc4673d1fbc5bac1f6d98fd3",
                ]));
        });
        
        $facade = $this->app->make(MailListFacade::class);
        
        $response = $facade->getMailList("lid.esac.nl");
        
        $this->assertNotNull($response);
        
        $this->assertEquals("lid.esac.nl",  $response->getId());
        $this->assertEquals("lid@esac.nl",  $response->getAddress());
        $this->assertEquals(0,              $response->getMembersCount());
        $this->assertEquals("Lid",          $response->getName());
        $this->assertCount(0,           $response->getMembers());
    }
    
    function test_non_existent_mail_list()
    {

        Http::fake([
            'http://localhost:8001/3.1/lists/nonexistent.esac.nl' => Http::response('Not Found', 404),
        ]);

        $facade = $this->app->make(MailListFacade::class);
        
        $response = $facade->getMailList("nonexistent.esac.nl");
        
        $this->assertNull($response);
    }
}

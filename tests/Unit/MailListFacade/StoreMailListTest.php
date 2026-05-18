<?php

namespace Tests\Unit\MailListFacade;

use App\CustomClasses\MailList\MailListFacade;
use App\CustomClasses\MailList\MailMan;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery\MockInterface;

class StoreMailListTest extends \TestCase
{
    public function testBasic()
    {
        $this->mock(MailMan::class, function (MockInterface $mock) {
            $mock->shouldReceive('post')
                ->withArgs([
                    '/lists',
                    ['fqdn_listname' => "test@esac.nl"]
                ])
                ->once();
        });
        
        $facade = $this->app->make(MailListFacade::class);
        
        $data = [
            'id' => "",
            'address' => "test",
            ];
        $facade->storeMailList($data);
    }
    
    public function test_already_existing_mail_list() {
        $exception = ClientException::create(new Request("POST", "http://localhost:8001/3.1/lists", [], '[fqdn_listname: "duplicate@esac.nl"]'), new Response(400, [], '{"title": "400 Bad Request", "description": "Mailing list exists"}'));
       
        //the facade shouldn't handle exception
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(400);
        
        $this->mock(MailMan::class, function (MockInterface $mock) use ($exception) {
            $mock->shouldReceive('post')
                ->withArgs([
                    '/lists',
                    ['fqdn_listname' => "duplicate@esac.nl"]
                ])
                ->once()
                ->andThrow($exception);
        });
        
        $facade = $this->app->make(MailListFacade::class);
        
        $data = [
            'id' => "",
            'address' => "duplicate",
        ];
        $facade->storeMailList($data);
    }
}

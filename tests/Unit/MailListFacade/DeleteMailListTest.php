<?php

namespace Tests\Unit\MailListFacade;

use App\CustomClasses\MailList\MailListFacade;
use App\CustomClasses\MailList\MailMan;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class DeleteMailListTest extends \TestCase
{
    public function test_delete_mail_list()
    {
        $this->mock(MailMan::class, function (MockInterface $mock) {
            $mock->shouldReceive('delete')
                ->with("/lists/test.esac.nl")
                ->once();
        });
        
        $facade = $this->app->make(MailListFacade::class);
        
        $facade->deleteMailList("test.esac.nl");
    }
    
    public function test_delete_non_existent_mail_list()
    {
        $exception = ClientException::create(new Request("DELETE", "http://localhost:8001/3.1/lists/test.esac.nl"), new Response(404, [], '{"title": "404 Not Found", "description": "404 Not Found"}'));
        
        //the facade shouldn't handle exception
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(404);
        
        $this->mock(MailMan::class, function (MockInterface $mock) use ($exception) {
            $mock->shouldReceive('delete')
                ->with("/lists/test.esac.nl")
                ->once()
                ->andThrow($exception);
        });
        $facade = $this->app->make(MailListFacade::class);
        $facade->deleteMailList("test.esac.nl");
    }
}

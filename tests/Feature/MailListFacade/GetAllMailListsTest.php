<?php

namespace Tests\Feature\MailListFacade;

use App\CustomClasses\MailList\MailList;
use App\CustomClasses\MailList\MailListFacade;
use App\CustomClasses\MailList\MailListMember;
use App\CustomClasses\MailList\MailMan;
use \TestCase;
use Mockery\MockInterface;

class GetAllMailListsTest extends TestCase
{
    private function createMailListObjects()
    {
        $mailLists = [];
        
        $list =  new MailList();
        $list->setId("alle-leden.esac.nl");
        $list->setName("Alle leden");
        $list->setAddress("alle-leden@esac.nl");
        $list->setMembersCount(10);
        
        array_push($mailLists, $list);
        
        $list =  new MailList();
        $list->setId("lid.esac.nl");
        $list->setName("Lid");
        $list->setAddress("lid@esac.nl");
        $list->setMembersCount(5);
        
        $list =  new MailList();
        $list->setId("reunist.esac.nl");
        $list->setName("Reunisten");
        $list->setAddress("reunist@esac.nl");
        $list->setMembersCount(10);
        
        array_push($mailLists, $list);
    }
    public function testBasic()
    {
        
//        $this->instance(MailMan::class, function (MockInterface $mock) {
//            $mock->shouldReceive('get')->once()->with('/lists')->andReturn([]);
//        });
//        $response = $this->get('/');
//
//        $response->assertStatus(200);
    }
}

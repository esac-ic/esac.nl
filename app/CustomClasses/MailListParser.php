<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 9-9-2017
 * Time: 19:14
 */

namespace App\CustomClasses;


use App\MailList;
use App\MailListMember;

/**
 * Class contains function to parse object to MailList object
 * Class MailListParser
 * @package App\CustomClasses
 */
class MailListParser
{

    /**
     * Parses the json mailList object from the mailgunapi to MailList object
     * @param $object
     * @return MailList
     */
    public function parseMailGunMailList($object){
        $mailList = new MailList();
        $mailList->acces_level = $object->access_level;
        $mailList->address = $object->address;
        $mailList->created_at = $object->created_at;
        $mailList->description = $object->description;
        $mailList->members_count = $object->members_count;
        $mailList->name = $object->name;

        return $mailList;
    }

    public function parseMailGunMember($object){
        $member = new MailListMember();
        $member->address = $object->address;
        $member->name = $object->name;
        $member->subscribed = $object->address;

        return $member;
    }
}
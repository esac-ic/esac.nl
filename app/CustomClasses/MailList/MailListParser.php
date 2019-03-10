<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 9-9-2017
 * Time: 19:14
 */

namespace App\CustomClasses\MailList;

/**
 * Class contains function to parse object to MailList object
 * Class MailListParser
 * @package App\CustomClasses
 */
class MailListParser
{

    /**
     * Parses the json mailList object from the mailman api to MailList object
     * @param $object
     * @return MailList
     */
    public function parseMailManMailList($object){
        $mailList = new MailList();
        $mailList
            ->setId($object->list_id)
            ->setAddress($object->fqdn_listname)
            ->setMembersCount($object->member_count)
            ->setName($object->display_name);

        return $mailList;
    }

    public function parseMailManMember($object){
        $member = new MailListMember();
        $member
            ->setAddress($object->email)
            ->setName($object->display_name);

        return $member;
    }

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
}
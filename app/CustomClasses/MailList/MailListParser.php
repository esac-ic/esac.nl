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
    public function parseMailManMailList($object): MailList
    {
        $mailList = new MailList();
        $mailList
            ->setId($object->list_id)
            ->setAddress($object->fqdn_listname)
            ->setMembersCount($object->member_count)
            ->setName($object->display_name);

        return $mailList;
    }

    /**
     * @param $object
     * @return MailListMember
     */
    public function parseMailManMember($object): MailListMember
    {
        $member = new MailListMember();
        $member
            ->setAddress($object->email)
            ->setName($object->display_name);

        return $member;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 9-9-2017
 * Time: 19:14
 */

namespace App\CustomClasses\MailList;

use ArrayAccess;

/**
 * Class contains function to parse object to MailList object
 * Class MailListParser
 * @package App\CustomClasses
 */
class MailListParser
{

    /**
     * Parses a raw mail list payload from the mailman api to a MailList object.
     */
    public function parseMailManMailList(array|ArrayAccess $raw): MailList
    {
        $mailList = new MailList();
        $mailList
            ->setId($raw['list_id'])
            ->setAddress($raw['fqdn_listname'])
            ->setMembersCount($raw['member_count'])
            ->setName($raw['display_name']);

        return $mailList;
    }

    /**
     * Parses a raw mailman member payload from the mailman api to a MailListMember object.
     */
    public function parseMailManMember(array|ArrayAccess $raw): MailListMember
    {
        $member = new MailListMember();
        $member
            ->setAddress($raw['email'])
            ->setName($raw['display_name']);

        return $member;
    }
}

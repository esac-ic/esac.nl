<?php
/**
 * Created by PhpStorm.
 * User: Niek
 * Date: 10-3-2019
 * Time: 18:55
 */

namespace App\CustomClasses\MailList;

class MailList
{
    private $id;
    private $address;
    private $members_count;
    private $name;
    private $members = array();

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return MailList
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     * @return MailList
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMembersCount()
    {
        return $this->members_count;
    }

    /**
     * @param mixed $members_count
     * @return MailList
     */
    public function setMembersCount($members_count)
    {
        $this->members_count = $members_count;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return MailList
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param MailListMember $members
     * @return MailList
     */
    public function addMember(MailListMember $member): MailList
    {
        array_push($this->members, $member);
        return $this;
    }
}

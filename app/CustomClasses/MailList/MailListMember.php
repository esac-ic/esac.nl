<?php
/**
 * Created by PhpStorm.
 * User: Niek
 * Date: 10-3-2019
 * Time: 21:01
 */

namespace App\CustomClasses\MailList;

class MailListMember
{
    private $name;
    private $address;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return MailListMember
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return MailListMember
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }
}

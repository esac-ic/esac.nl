<?php

namespace App\CustomClasses\Eboekhouden;

use App\User;

class EboekhoudenFacade
{
    private $eboekhoudenHandler;
    
    public function __construct(Eboekhouden $eboekhoudenHandler)
    {
        $this->eboekhoudenHandler = $eboekhoudenHandler;
    }
    
    public function createMember(User $user)
    {
        $this->eboekhoudenHandler->post('/member', [
            "memberNumber" => "MEM-" . $user->id,
            "name" => $user->getName(),
            "address" => $user->getAddress(),
            "postalCode" => $user->zipcode,
            "city" => $user->city,
            "country" => $user->country,
            "phoneNumber" => $user->phonenumber,
            "emailAddress" => $user->email,
            "emailAddressInvoice" => $user->email,
            "emailAddressReminder" => $user->email,
            "note" => $user->remark, //TODO maybe don't add this
            "iban" => $user->IBAN,
            "bic" => $user->BIC,
//            "ledgerId" => 4500, //contributies and abonnementen
            "mandate" => $user->incasso,
            "mandateType" => "D",
            "mandateId" => "MAND12345",
            "mandateSignedDate" => "2023-10-01",
        ]);
    }
    
    public function getAllLedgers()
    {
        return $this->eboekhoudenHandler->get('/ledger');
    }
}
<?php
namespace App\CustomClasses\Eboekhouden;

use App\Models\User;
use Exception;

class EboekhoudenFacade
{
    private $eboekhoudenHandler;
    
    public function __construct(Eboekhouden $eboekhoudenHandler)
    {
        $this->eboekhoudenHandler = $eboekhoudenHandler;
    }

    protected function getRelationInfo(User $user)
    {
        if($user->isPendingMember()) throw new Exception('A pending member should not be added to eboekhouden');

        if(strlen($user->getName()) > 100) throw new Exception('The users name is too long to be added to eboekhouden');
        if(strlen($user->getAddress()) > 150) throw new Exception('The users adress is too long to be added to eboekhouden');
        if(strlen($user->zipcode) > 50) throw new Exception('The users zipcode is too long to be added to eboekhouden');
        if(strlen($user->city) > 50) throw new Exception('The users city is too long to be added to eboekhouden');
        if(strlen($user->country) > 50) throw new Exception('The users country is too long to be added to eboekhouden');
        if(strlen($user->phonenumber) > 50) throw new Exception('The users phonenumber is too long to be added to eboekhouden');
        if(strlen($user->email) > 50) throw new Exception('The users emailAddress is too long to be added to eboekhouden');
        if(strlen($user->IBAN) > 50) throw new Exception('The users IBAN is too long to be added to eboekhouden');
        if(strlen($user->BIC) > 50) throw new Exception('The users BIC is too long to be added to eboekhouden');

        return [
            "Code" => "lid_" . $user->id,
            "name" => $user->getName(),
            "address" => $user->getAddress(),
            "postalCode" => $user->zipcode,
            "city" => $user->city,
            "country" => $user->country,
            "phoneNumber" => $user->phonenumber,
            "emailAddress" => $user->email,
            "emailAddressInvoice" => $user->email,
            "emailAddressReminder" => $user->email,
            "iban" => $user->IBAN,
            "bic" => $user->BIC,
            "mandate" => $user->incasso,
            "mandateType" => "D",
            "mandateSignedDate" => $user->created_at->format('Y-m-d'),
            'salutation' =>$user->kind_of_member,
            'type' => 'P',
        ];
    }
    
    public function createRelation(User $user)
    {
        $this->eboekhoudenHandler->post('/relation', $this->getRelationInfo($user));
    }

    public function updateRelation(User $user)
    {
        $this->eboekhoudenHandler->patch('/relation', $this->getRelationInfo($user));
    }

}

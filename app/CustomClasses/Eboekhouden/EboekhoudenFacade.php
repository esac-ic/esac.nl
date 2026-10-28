<?php
namespace App\CustomClasses\Eboekhouden;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\RequestException;

class EboekhoudenFacade
{
    private $eboekhoudenHandler;
    
    public function __construct(Eboekhouden $eboekhoudenHandler)
    {
        $this->eboekhoudenHandler = $eboekhoudenHandler;
    }

    protected function getRelationCode(User $user) {
        return "lid_{$user->id}";
    }
    protected function getRelationInfo(User $user, string $notes = null)
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
        if(strlen($notes) > 40000) throw new Exception('Received relation note that is too long to be added to eboekhouden');

        return [
            'code' => $this->getRelationCode($user),
            'name' => $user->getName(),
            'address' => $user->getAddress(),
            'postalCode' => $user->zipcode,
            'city' => $user->city,
            'country' => $user->country,
            'phoneNumber' => $user->phonenumber,
            'emailAddress' => $user->email,
            'emailAddressInvoice' => $user->email,
            'emailAddressReminder' => $user->email,
            'iban' => $user->IBAN,
            'bic' => $user->BIC,
            'mandate' => $user->incasso == 1,
            'mandateType' => 'D',
            'mandateSignedDate' => $user->created_at->format('Y-m-d'),
            'termOfPayment' => 21,
            'type' => 'P',
            'note' => $notes
        ];
    }
    
    public function createRelation(User $user)
    {
        // The eboekhouden API should catch this, but just in case they change their behaviour
        //  check if the user isn't already in eboekhouden
        $users = $this->eboekhoudenHandler->get("/v1/relation?code={$this->getRelationCode($user)}");
        if($users['count'] > 0) throw new Exception('User already exists in eboekhouden, cannot create the relationship twice');

        $date = Carbon::now()->toDateTimeString();
        $result = $this->eboekhoudenHandler->post('/v1/relation', 
            $this->getRelationInfo($user, "[{$date}] Created by esac.nl")
        );

        $user->eboekhouden_id = $result['id'];
        $user->save();

        // TODO add a subscription (once this is possible with the eboekhouden API)
    }

    public function updateRelation(User $user)
    {
        if($user->eboekhouden_id == null) throw new Exception('Cannot update a user that does not have an eboekhouden id (it hasn\'t been added to eboekhouden yet)');

        $response = $this->eboekhoudenHandler->get("/v1/relation/{$user->eboekhouden_id}");
        if($response['code'] != $this->getRelationCode($user))
            throw new Exception("User code wasn\'t the expected value in eboekhouden (found {$response['code']} instead of {$this->getRelationCode($user)}");


        $currentNotes = $response['note'];
        $date = Carbon::now()->toDateTimeString();
        $this->eboekhoudenHandler->patch("/v1/relation/{$user->eboekhouden_id}",
            $this->getRelationInfo($user, $currentNotes . "\n[{$date}] Updated by esac.nl")
        );

        // TODO possibly update the subscription (once this is possible with the eboekhouden API)
    }

}

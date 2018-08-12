<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 9-9-2017
 * Time: 18:59
 */

namespace App\CustomClasses;


use App\MailList;
use App\User;
use Bogardo\Mailgun\Facades\Mailgun;

class MailgunFacade
{
    private $_mailListParser;

    public function __construct(MailListParser $mailListParser)
    {
        $this->_mailListParser = $mailListParser;
    }

    public function getAllMailLists(){
        $mailLists = array();
        foreach(Mailgun::api()->get("lists/pages")->http_response_body->items as $mailList){
            array_push($mailLists,$this->_mailListParser->parseMailGunMailList($mailList));
        }
        return $mailLists;
    }

    public function getMailList($id){
        //https://api.mailgun.net/v3/lists/leden@esac.nl/members/pages?page=next&address=gerbenopdenkamp%40hotmail.com&limit=100
        $mailList = $this->_mailListParser->parseMailGunMailList(Mailgun::api()->get('lists/' . $id)->http_response_body->list);
        $urlQuery = [];
        for($i=0; $i <= $mailList->members_count; $i += 100){
            $lastMember = "";
            foreach (Mailgun::api()->get('lists/' . $id  .'/members/pages',$urlQuery)->http_response_body->items as $member){
                $member = $this->_mailListParser->parseMailGunMember($member);
                $mailList->addMember($member);
                $lastMember = $member->address;
            }
            $urlQuery = [
              'page' => 'next',
              'address' => $lastMember,
              'limit' => 100
            ];
        }

        return $mailList;
    }

    public function storeMailList(MailList $mailList){
        Mailgun::api()->post("lists", [
            'address'      => $mailList->address,
            'name'         => $mailList->name,
            'description'  => $mailList->description,
            'access_level' => $mailList->acces_level
        ]);
    }
    public function updateMailList($id,MailList $mailList){
        Mailgun::api()->put("lists/" . $id, [
            'address'      => $mailList->address,
            'name'         => $mailList->name,
            'description'  => $mailList->description,
            'access_level' => $mailList->acces_level
        ]);
    }

    public function deleteMailList($id){
        Mailgun::api()->delete("lists/" . $id);
    }

    public function deleteMemberFromMailList($mailListId,$memberId){
        Mailgun::api()->delete("lists/" . $mailListId . "/members/". $memberId);
    }

    public function addMember($mailListId, $email,$name){
        Mailgun::api()->post('lists/' . $mailListId .'/members', [
            'address' => $email,
            'name' => $name
        ] );
    }

    public function deleteUserFormAllMailList($user){
        foreach ($this->getAllMailLists() as $mailList){
            try {
                //we used try to delete the user from the mail list wihout checken if he is in the list because
                //that take to much time
                $this->deleteMemberFromMailList($mailList->address,$user->email);
            } catch (\Exception $e){
            }
        }
    }

    public function updateUserEmailFormAllMailList($user, $oldEmail, $newEmail){
        foreach ($this->getAllMailLists() as $mailList){
            $mailList = $this->getMailList($mailList->address);
            foreach ($mailList->members as $member){
                if($oldEmail === $member->address){
                    $this->deleteMemberFromMailList($mailList->address,$member->address);
                    $this->addMember($mailList->address,$newEmail,$user->getName());
                }
            }

        }
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 9-9-2017
 * Time: 18:59
 */

namespace App\CustomClasses\MailList;

class MailListFacade
{
    private $_mailListParser;
    private $_mailManHandler;

    public function __construct(MailListParser $mailListParser, MailMan $mailMan)
    {
        $this->_mailListParser = $mailListParser;
        $this->_mailManHandler = $mailMan;
    }

    /**
     * Returns an array of MailList objects for each maillist that exists in mailman
     * 
     * @return array mailLists
     */
    public function getAllMailLists()
    {
        $mailLists = array();
        $response = $this->_mailManHandler->get('/lists');
        foreach ($response->entries as $mailList) {
            array_push($mailLists, $this->_mailListParser->parseMailManMailList($mailList));
        }
        return $mailLists;
    }
    
    /**
     * Returns the ids of all maillists
     * 
     * @return array mailListIds
     */
    public function getAllMailListIds()
    {
        $mailListIds = array();
        $response = $this->_mailManHandler->get('/lists');
        foreach ($response->entries as $mailList) {
            array_push($mailListIds, $this->_mailListParser->parseMailManMailList($mailList)->getId());
        }
        return $mailListIds;
    }

    public function getMailList($id)
    {
        $mailList = $this->_mailListParser->parseMailManMailList($this->_mailManHandler->get('/lists/' . $id));
        $members = $this->_mailManHandler->get('/lists/' . $id . '/roster/member');

        if (property_exists($members, "entries")) {
            foreach ($members->entries as $member) {
                $parsedMember = $this->_mailListParser->parseMailManMember($member);
                $mailList->addMember($parsedMember);
            }
        }

        return $mailList;
    }

    public function storeMailList(array $data)
    {
        $this->_mailManHandler->post("/lists", [
            'fqdn_listname' => $data['address'] . env("MAIL_MAN_DOMAIN"),
        ]);
    }

    public function deleteMailList($id)
    {
        $this->_mailManHandler->delete("/lists/" . $id);
    }

    public function deleteMemberFromMailList($mailListId, $memberEmail)
    {
        $this->_mailManHandler->delete("/lists/" . $mailListId . "/member/" . $memberEmail);
    }

    public function addMember($mailListId, $email, $name)
    {
        $this->_mailManHandler->post(
            "/members",
            [
                "list_id" => $mailListId,
                "subscriber" => $email,
                "display_name" => $name,
                "pre_verified" => true,
                "pre_confirmed" => true,
                "pre_approved" => true,
            ]
        );
    }

    public function deleteUserFormAllMailList($user)
    {
        foreach ($this->getAllMailLists() as $mailList) {
            try {
                //we used try to delete the user from the mail list wihout checken if he is in the list because
                //that take to much time
                $this->deleteMemberFromMailList($mailList->getId(), $user->email);
            } catch (\Exception $e) {
            }
        }
    }

    public function updateUserEmailFormAllMailList($user, $oldEmail, $newEmail)
    {
        foreach ($this->getAllMailLists() as $mailList) {
            $mailList = $this->getMailList($mailList->getAddress());
            foreach ($mailList->getMembers() as $member) {
                if ($oldEmail === $member->getAddress()) {
                    $this->deleteMemberFromMailList($mailList->getId(), $member->getAddress());
                    $this->addMember($mailList->getId(), $newEmail, $user->getName());
                }
            }

        }
    }
    
    /**
     * Adds a user to an array of maillists
     * 
     * @param string $email the email address of the user
     * @param string $name the name of the user
     * @param array $mailLists an array of mailist to add the user to
     * 
     * @return void
     */
    public function addUserToSpecifiedMailLists(string $email, string $name, Array $mailLists) 
    {
        if ($mailLists && $mailLists != [""]) { //check if mailLists is not empty
            $allMailLists = $this->getAllMailListIds();
            
            foreach ($mailLists as $mailList) {
                //check if the mailist exists and then add the user to the mail list
                if (in_array($mailList, $allMailLists)) {
                    $this->addMember($mailList, $email, $name);
                    \Log::info("added user " . $name . " to mailists: " . $mailList);
                } else {
                    //TODO: possibly display an error in the gui here somehow
                    \Log::error("tried adding user to maillist " . $mailList . " while this list doesn't exist");
                }
            }
        }
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 9-9-2017
 * Time: 18:59
 */

namespace App\CustomClasses\MailList;

use \Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use \Session;

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
     * @return MailList[] mailLists
     */
    public function getAllMailLists(): array
    {
        $mailLists = array();
        $response = $this->_mailManHandler->get('/lists');
        
        if ($response->total_size == 0) return [];
        
        foreach ($response->entries as $mailList) {
            array_push($mailLists, $this->_mailListParser->parseMailManMailList($mailList));
        }
        return $mailLists;
    }
    
    /**
     * Returns the ids of all maillists
     * 
     * @return string[] mailListIds
     */
    public function getAllMailListIds(): array
    {
        $mailListIds = array();
        $response = $this->_mailManHandler->get('/lists');
        
        if ($response->total_size == 0) return [];
        
        foreach ($response->entries as $mailList) {
            array_push($mailListIds, $this->_mailListParser->parseMailManMailList($mailList)->getId());
        }
        return $mailListIds;
    }

    public function getMailList($id): ?MailList
    {
        try {
            $response = $this->_mailManHandler->get('/lists/' . $id);
            $mailList = $this->_mailListParser->parseMailManMailList($response);
            $members = $this->_mailManHandler->get('/lists/' . $id . '/roster/member');
            
            if (property_exists($members, "entries")) {
                foreach ($members->entries as $member) {
                    $parsedMember = $this->_mailListParser->parseMailManMember($member);
                    $mailList->addMember($parsedMember);
                }
            }
            return $mailList;
        } catch (RequestException $e) {
            Log::error($e->getMessage());
            return null;
        }
    }
    
    /**
     * Stores a mail list in mailman.
     *
     * @param array $data the data containing the address of the mail list
     * @return void
     * @throws ClientException When the mail list already exists
     */
    public function storeMailList(array $data): void
    {
        $this->_mailManHandler->post("/lists", [
            'fqdn_listname' => $data['address'] . env("MAIL_MAN_DOMAIN"),
        ]);
    }

    public function deleteMailList($id): void
    {
        $this->_mailManHandler->delete("/lists/" . $id);
    }

    public function deleteMemberFromMailList($mailListId, $memberEmail, $suppressErrors = false): void
    {
        try {
            $this->_mailManHandler->delete("/lists/" . $mailListId . "/member/" . $memberEmail);
        } catch(Exception $e) {
            if (!$suppressErrors)
            {
                Log::error($e->getMessage());
            }
        }
    }

    public function addMember($mailListId, $email, $name): void
    {
        try {
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
        } catch (Exception $e) {
            \Log::error($e->getMessage());
        }
    }

    public function deleteUserFormAllMailList($user): void
    {
        foreach ($this->getAllMailListIds() as $mailListId) {
            try {
                //we used try{} to delete the user from the mail list without checking if they are in the list because
                //that takes too much time
                $this->deleteMemberFromMailList($mailListId, $user->email, $suppressErrors = true);
            } catch (Exception $e) {
            }
        }
    }

    public function updateUserEmailFormAllMailList($user, $oldEmail, $newEmail): void
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
    public function addUserToSpecifiedMailLists(string $email, string $name, Array $mailLists): void
    {
        if ($mailLists && $mailLists != [""]) { //check if mailLists is not empty
            $allMailLists = $this->getAllMailListIds();
            
            foreach ($mailLists as $mailList) {
                //check if the mailist exists and then add the user to the mail list
                if (in_array($mailList, $allMailLists)) {
                    $this->addMember($mailList, $email, $name);
                } else {
                    \Log::error("Error: tried to add user " . $email . " to nonexistent maillist " . $mailList);
                }
            }
        }
    }
    
    public function removeUserFromSpecifiedMailLists(string $email, Array $mailLists): void
    {
        if ($mailLists && $mailLists != [""]) { //check if mailLists is not empty
            $allMailLists = $this->getAllMailListIds();
            
            foreach ($mailLists as $mailList) {
                //check if the mail list exists and then remove the user from the mail list
                if (in_array($mailList, $allMailLists)) {
                    $this->deleteMemberFromMailList($mailList, $email);
                } else {
                    \Log::error("Error: tried to remove user " . $email . " from nonexistent maillist " . $mailList);
                }
            }
        }
    }
    
    /**
     * Deletes all users from a specified mail list.
     * 
     * @param string $mailListId the Id of the maillist that is to be emptied
     * @param array|null $allMailListIds optional parameter that can be used to prevent multiple API calls to get all maillist ids when the function is called repeatedly
     * 
     * @return void
     */
    public function deleteAllUsersFromMailList(string $mailListId, array|null $allMailListIds = null): void
    {
        if ($mailListId)
        {
            if ($allMailListIds == null)
            {
                $allMailListIds = $this->getAllMailListIds();
            }
            
            if (in_array($mailListId, $allMailListIds))
            {
                try {
                    //get all members of the maillist
                    $members = $this->getMailList($mailListId)->getMemberEmails();
                    
                    foreach ($members as $member)
                    {
                        //TODO this is a somewhat hacky and inefficient way to do it,
                        // but the "nicer" way below isn't working properly for some reason.
                        $this->deleteMemberFromMailList($mailListId, $member);
                    }
                    
                    //The below code could be used to call the mass unsubscription api function from mailman,
                    // but it's not really working for some reason as of october 2025.
                    // if ($members != [])
                    // {
                    //     //delete all members
                    //     $this->_mailManHandler->delete(
                    //         "/lists/" . $mailListId . "/roster/member",
                    //         [
                    //             'emails' => json_encode($members),//array("member@esac.nl","test@esac.nl"),//$members, //not sure if this already works since it's including names
                    //             ]
                    //         );
                    //     }     
                        
                        
                    } catch(Exception $e) {
                    \Log::error($e->getMessage());
                }
            }
        }
    }
    
}

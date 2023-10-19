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

    public function getAllMailLists()
    {
        $mailLists = array();
        $response = $this->_mailManHandler->get('/lists');
        foreach ($response->entries as $mailList) {
            array_push($mailLists, $this->_mailListParser->parseMailManMailList($mailList));
        }
        return $mailLists;
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

}

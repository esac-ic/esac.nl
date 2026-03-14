<?php

namespace App\CustomClasses\MailList;

use App\User;
use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class MailListFacade
{
    private MailListParser $_mailListParser;
    private MailMan $_mailManHandler;
    private string $_domain;

    public function __construct(MailListParser $mailListParser, MailMan $mailMan)
    {
        $this->_mailListParser = $mailListParser;
        $this->_mailManHandler = $mailMan;
        $this->_domain = config('mailman.domain');
    }

    /**
     * Returns an array of MailList objects for each mail list that exists in mailman
     *
     * @return Collection mailLists
     * @throws RequestException
     */
    public function getAllMailLists(): Collection
    {
        $entries = $this->_mailManHandler->get('/lists')->get('entries', []);
        return collect($entries)->map($this->_mailListParser->parseMailManMailList(...));
    }
    
    /**
     * Returns the ids of all mail lists
     * 
     * @return string[] mailListIds
     * @throws RequestException
     */
    public function getAllMailListIds(): array
    {
        return $this->getAllMailLists()
            ->map(fn (MailList $list) => $list->getId())
            ->all();
    }

    public function getMailList($id): ?MailList
    {
        try {
            $mailList = $this->_mailListParser->parseMailManMailList($this->_mailManHandler->get('/lists/' . $id));
            $entries = $this->_mailManHandler->get('/lists/' . $id . '/roster/member')->get('entries', []);
            collect($entries)
                ->map($this->_mailListParser->parseMailManMember(...))
                ->each(fn ($member) => $mailList->addMember($member));

            return $mailList;
        } catch (RequestException $e) {
            Log::error($e->getMessage());
            return null;
        }
    }

    /** @throws RequestException */
    public function getMailListsForMember(string $member): Collection
    {
        $entries = $this->_mailManHandler->post('/lists/find', ['subscriber' => $member])->get('entries', []);
        return collect($entries)->map($this->_mailListParser->parseMailManMailList(...));
    }
    
    /**
     * Stores a mail list in mailman.
     *
     * @param array $data the data containing the address of the mail list
     * @return void
     * @throws RequestException When the mail list already exists
     */
    public function storeMailList(array $data): void
    {
        $this->_mailManHandler->post("/lists", [
            'fqdn_listname' => $data['address'] . $this->_domain,
        ]);
    }

    /**
     * @throws RequestException When the mail list couldn't be deleted.
     */
    public function deleteMailList($id): void
    {
        $this->_mailManHandler->delete("/lists/" . $id);
    }
    
    /**
     * Deletes/removes a member from a mail list.
     *
     * @param string $mailListId id of the mail list
     * @param string $memberEmail email of the member to be deleted
     * @param bool $suppressErrors option to suppress errors when they are encountered
     *
     * @throws RequestException when the member or list doesn't exist
     */
    public function deleteMemberFromMailList(string $mailListId, string $memberEmail, bool $suppressErrors = false): void
    {
        try {
            $this->_mailManHandler->delete("/lists/" . $mailListId . "/member/" . $memberEmail);
        } catch(RequestException $e) {
            if (!$suppressErrors)
            {
                throw $e;
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
    
    /** @throws RequestException */
    public function deleteUserFormAllMailList(User $user): void
    {
        $this->getMailListsForMember($user->email)
            ->each(fn (MailList $list) => $this->deleteMemberFromMailList($list->getId(), $user->email));
    }

    /** @throws RequestException */
    public function updateUserEmailFormAllMailList($user, $oldEmail, $newEmail): void
    {
        $listIds = $this->getMailListsForMember($oldEmail)->map(fn (MailList $list) => $list->getId());

        $listIds->each(fn($id) => $this->deleteMemberFromMailList($id, $oldEmail));
        $this->addUserToSpecifiedMailLists($newEmail, $user->getName(), $listIds);
    }
    
    /**
     * Adds a user to an array of mail lists
     * 
     * @param string $email the email address of the user
     * @param string $name the name of the user
     * @param array|Arrayable $mailListIds an array of mail list to add the user to
     * 
     * @return void
     * @throws RequestException
     */
    public function addUserToSpecifiedMailLists(string $email, string $name, array|Arrayable $mailListIds): void
    {
        $mailListIds = collect($mailListIds)->filter();
        if ($mailListIds->isEmpty()) {
            return;
        }

        $allListIds = $this->getAllMailListIds();
        if ($mailListIds->diff($allListIds)->isNotEmpty()) {
            \Log::warning('Warning: tried to add user '.$email.' to nonexistent mail list(s): ',
                ['list_ids' => $mailListIds->diff($allListIds)->all()]);
        }

        collect($mailListIds)
            ->intersect($allListIds)
            ->each(fn ($listId) => $this->addMember($listId, $email, $name));
    }

    /** @throws RequestException */
    public function removeUserFromSpecifiedMailLists(string $email, array|Arrayable $mailListIds): void
    {
        $mailListIds = collect($mailListIds)->filter();
        if ($mailListIds->isEmpty()) {
            return;
        }

        $allListIds = $this->getAllMailListIds();
        if (collect($mailListIds)->diff($allListIds)->isNotEmpty()) {
            \Log::warning('Warning: tried to remove user '.$email.' from nonexistent mail list(s): ',
                ['list_ids' => collect($mailListIds)->diff($allListIds)->all()]);
        }

        collect($mailListIds)
            ->intersect($allListIds)
            ->each(fn($listId) => $this->deleteMemberFromMailList($listId, $email));
    }

    /**
     * Deletes all users from a specified mail list.
     *
     * @param  string  $mailListId  the ID of the mail list that is to be emptied
     * @return void
     * @throws RequestException
     */
    public function deleteAllUsersFromMailList(string $mailListId): void
    {
        try {
            $this->_mailManHandler->delete('/lists/'.$mailListId.'/roster/member', [
                'emails' => $this->getMailList($mailListId)->getMemberEmails(),
            ]);
        } catch (RequestException $e) {
            if ($e->getCode() === 404) {
                Log::warning("Tried to delete all users from mail list that doesn't exist: $mailListId");
                return;
            }
            throw $e;
        }
    }
}

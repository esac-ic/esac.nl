<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Fluent;

/**
 * Client for Mailman's REST API. Mail lists and members are returned as Fluent objects.
 */
class Mailman
{
    private function http(): PendingRequest
    {
        return Http::baseUrl((string) config('services.mailman.url'))->withBasicAuth(
            config('services.mailman.username'), config('services.mailman.password'),
        );
    }

    /** @throws RequestException */
    private function get(string $url): Collection
    {
        return $this->http()->get($url)->throw()->collect();
    }

    /** @throws RequestException */
    private function post(string $url, array $body): Collection
    {
        return $this->http()->post($url, $body)->throw()->collect();
    }

    /** @throws RequestException */
    private function delete(string $url, array $body = []): Collection
    {
        return $this->http()->delete($url, $body)->throw()->collect();
    }

    private function toMailList(array $raw): Fluent
    {
        return new Fluent([
            'id' => $raw['list_id'],
            'address' => $raw['fqdn_listname'],
            'members_count' => $raw['member_count'],
            'name' => $raw['display_name'],
            'members' => [],
        ]);
    }

    private function toMember(array $raw): Fluent
    {
        return new Fluent([
            'address' => $raw['email'],
            'name' => $raw['display_name'],
        ]);
    }

    /**
     * Returns every mail list that exists in mailman, without members
     *
     * @return Collection<int, Fluent>
     * @throws RequestException
     */
    public function getAllMailLists(): Collection
    {
        return collect($this->get('/lists')->get('entries', []))->map($this->toMailList(...));
    }

    /**
     * Returns the ids of all mail lists
     *
     * @return string[] mailListIds
     * @throws RequestException
     */
    public function getAllMailListIds(): array
    {
        return $this->getAllMailLists()->pluck('id')->all();
    }

    public function getMailList($id): ?Fluent
    {
        try {
            $mailList = $this->toMailList($this->get('/lists/' . $id)->all());
            $mailList->members = collect($this->get('/lists/' . $id . '/roster/member')->get('entries', []))
                ->map($this->toMember(...))
                ->all();

            return $mailList;
        } catch (RequestException $e) {
            Log::error($e->getMessage());
            return null;
        }
    }

    /** @throws RequestException */
    public function getMailListsForMember(string $member): Collection
    {
        try {
            $entries = $this->post('/lists/find', ['subscriber' => $member])->get('entries', []);
            return collect($entries)->map($this->toMailList(...));
        } catch (RequestException $e) {
            //mailman throws a 404 exception when a member is in no mail lists, so we don't want to catch this.
            if ($e->getCode() === 404) {
                return collect();
            } else {
                throw $e;
            }
        }
    }

    /**
     * Stores a mail list in mailman.
     *
     * @param array $data the data containing the address of the mail list
     * @throws RequestException When the mail list already exists
     */
    public function storeMailList(array $data): void
    {
        $this->post("/lists", [
            'fqdn_listname' => $data['address'] . config('services.mailman.domain'),
        ]);
    }

    /**
     * @throws RequestException When the mail list couldn't be deleted.
     */
    public function deleteMailList($id): void
    {
        $this->delete("/lists/" . $id);
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
            $this->delete("/lists/" . $mailListId . "/member/" . $memberEmail);
        } catch (RequestException $e) {
            if (!$suppressErrors) {
                throw $e;
            }
        }
    }

    public function addMember($mailListId, $email, $name): void
    {
        try {
            $this->post("/members", [
                "list_id" => $mailListId,
                "subscriber" => $email,
                "display_name" => $name,
                "pre_verified" => true,
                "pre_confirmed" => true,
                "pre_approved" => true,
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /** @throws RequestException */
    public function deleteUserForAllMailList(User $user): void
    {
        $this->getMailListsForMember($user->email)
            ->each(fn (Fluent $list) => $this->deleteMemberFromMailList($list->id, $user->email));
    }

    /** @throws RequestException */
    public function updateUserEmailForAllMailList($user, $oldEmail, $newEmail): void
    {
        $listIds = $this->getMailListsForMember($oldEmail)->pluck('id');

        $listIds->each(fn ($id) => $this->deleteMemberFromMailList($id, $oldEmail));
        $this->addUserToSpecifiedMailLists($newEmail, $user->getName(), $listIds);
    }

    /**
     * Adds a user to an array of mail lists
     *
     * @param string $email the email address of the user
     * @param string $name the name of the user
     * @param array|Arrayable $mailListIds an array of mail list to add the user to
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
            Log::warning('Warning: tried to add user '.$email.' to nonexistent mail list(s): ',
                ['list_ids' => $mailListIds->diff($allListIds)->all()]);
        }

        $mailListIds
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
        if ($mailListIds->diff($allListIds)->isNotEmpty()) {
            Log::warning('Warning: tried to remove user '.$email.' from nonexistent mail list(s): ',
                ['list_ids' => $mailListIds->diff($allListIds)->all()]);
        }

        $mailListIds
            ->intersect($allListIds)
            //suppress errors so we don't break when a member is no longer part of a mail list
            ->each(fn ($listId) => $this->deleteMemberFromMailList($listId, $email, true));
    }
}

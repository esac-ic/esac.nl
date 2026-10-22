<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Fluent;

/**
 * Client for Mailman's REST API. Mail lists and members are returned as Fluent objects.
 * Failed requests throw a RequestException.
 */
class Mailman
{
    private function http(): PendingRequest
    {
        return Http::baseUrl((string) config('services.mailman.url'))
            ->withBasicAuth((string) config('services.mailman.username'), (string) config('services.mailman.password'))
            ->timeout(10);
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
     * Every mail list, without its members.
     *
     * @return Collection<int, Fluent>
     *
     * @throws RequestException
     */
    public function getAllMailLists(): Collection
    {
        return $this->http()->get('/lists')->throw()->collect('entries')->map($this->toMailList(...));
    }

    /**
     * The ids of all mail lists, fetched once per instance.
     *
     * @return string[]
     *
     * @throws RequestException
     */
    public function getAllMailListIds(): array
    {
        return once(fn () => $this->getAllMailLists()->pluck('id')->all());
    }

    /** @throws RequestException */
    public function getMailList(string $id): Fluent
    {
        $mailList = $this->toMailList($this->http()->get("/lists/$id")->throw()->json());
        $mailList->set('members', $this->http()->get("/lists/$id/roster/member")->throw()
            ->collect('entries')
            ->map($this->toMember(...))
            ->all());

        return $mailList;
    }

    /**
     * @return Collection<int, Fluent>
     *
     * @throws RequestException
     */
    public function getMailListsForMember(string $member): Collection
    {
        // Mailman answers 404 when the member is on no list.
        $response = $this->http()->post('/lists/find', ['subscriber' => $member])->throwUnlessStatus(404);

        return $response->notFound() ? collect() : $response->collect('entries')->map($this->toMailList(...));
    }

    /**
     * @throws RequestException when the mail list already exists
     */
    public function storeMailList(string $address): void
    {
        $this->http()->post('/lists', [
            'fqdn_listname' => $address.config('services.mailman.domain'),
        ])->throw();
    }

    /** @throws RequestException */
    public function deleteMailList(string $id): void
    {
        $this->http()->delete("/lists/$id")->throw();
    }

    /**
     * Removes the member from the mail list; a member who isn't on it (404) is fine.
     *
     * @throws RequestException
     */
    public function deleteMemberFromMailList(string $mailListId, string $memberEmail): void
    {
        $this->http()->delete("/lists/$mailListId/member/$memberEmail")->throwUnlessStatus(404);
    }

    /**
     * Adds the member to the mail list; a member who is already on it (409) is fine.
     *
     * @throws RequestException
     */
    public function addMember(string $mailListId, string $email, string $name): void
    {
        $this->http()->post('/members', [
            'list_id' => $mailListId,
            'subscriber' => $email,
            'display_name' => $name,
            'pre_verified' => true,
            'pre_confirmed' => true,
            'pre_approved' => true,
        ])->throwUnlessStatus(409);
    }

    /** @throws RequestException */
    public function deleteUserForAllMailList(User $user): void
    {
        $this->getMailListsForMember($user->email)
            ->each(fn (Fluent $list) => $this->deleteMemberFromMailList($list->get('id'), $user->email));
    }

    /** @throws RequestException */
    public function updateUserEmailForAllMailList(User $user, string $oldEmail, string $newEmail): void
    {
        $listIds = $this->getMailListsForMember($oldEmail)->pluck('id');

        $listIds->each(fn (string $id) => $this->deleteMemberFromMailList($id, $oldEmail));
        $this->addUserToSpecifiedMailLists($newEmail, $user->getName(), $listIds);
    }

    /**
     * Adds a member to the given mail lists that exist.
     *
     * @param  array<int, string>|Arrayable<int, string>  $mailListIds
     *
     * @throws RequestException
     */
    public function addUserToSpecifiedMailLists(string $email, string $name, array|Arrayable $mailListIds): void
    {
        $this->existingMailLists($mailListIds, $email)->each(fn (string $listId) => $this->addMember($listId, $email, $name));
    }

    /**
     * Removes a member from the given mail lists that exist.
     *
     * @param  array<int, string>|Arrayable<int, string>  $mailListIds
     *
     * @throws RequestException
     */
    public function removeUserFromSpecifiedMailLists(string $email, array|Arrayable $mailListIds): void
    {
        $this->existingMailLists($mailListIds, $email)->each(fn (string $listId) => $this->deleteMemberFromMailList($listId, $email));
    }

    /**
     * The given mail lists that exist in Mailman; the others are logged.
     *
     * @param  array<int, string>|Arrayable<int, string>  $mailListIds
     * @return Collection<int, string>
     */
    private function existingMailLists(array|Arrayable $mailListIds, string $email): Collection
    {
        $mailListIds = collect($mailListIds)->filter();
        if ($mailListIds->isEmpty()) {
            return $mailListIds;
        }

        $missing = $mailListIds->diff($this->getAllMailListIds());
        if ($missing->isNotEmpty()) {
            Log::warning("Mail lists of $email don't exist in Mailman", ['list_ids' => $missing->all()]);
        }

        return $mailListIds->intersect($this->getAllMailListIds());
    }
}

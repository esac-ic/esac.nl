<?php

namespace App\Services;

use App\Exceptions\MailmanException;
use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Fluent;

/**
 * Client for Mailman's REST API. Mail lists and members are returned as Fluent objects.
 * Failed requests throw a MailmanException.
 */
class Mailman
{
    /**
     * @param  'get'|'post'|'delete'  $method
     * @param  (Closure(Response): bool)|null  $isFine  which failed responses are fine
     */
    private function request(string $method, string $url, array $data = [], ?Closure $isFine = null): Response
    {
        try {
            return Http::baseUrl((string) config('services.mailman.url'))
                ->withBasicAuth(config('services.mailman.username'), config('services.mailman.password'))
                ->timeout(10)
                ->{$method}($url, $data)
                ->throwUnless($isFine ?? false);
        } catch (RequestException|ConnectionException $e) {
            throw new MailmanException('Mailman failed: '.$e->getMessage(), $e->getCode(), $e);
        }
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
     * @throws MailmanException
     */
    public function mailLists(): Collection
    {
        return $this->request('get', '/lists')->collect('entries')->map($this->toMailList(...));
    }

    /**
     * The ids of all mail lists, fetched once per instance.
     *
     * @return string[]
     *
     * @throws MailmanException
     */
    public function mailListIds(): array
    {
        return once(fn () => $this->mailLists()->pluck('id')->all());
    }

    /** @throws MailmanException */
    public function mailList(string $id): Fluent
    {
        $mailList = $this->toMailList($this->request('get', "/lists/$id")->json());
        $mailList->set('members', $this->request('get', "/lists/$id/roster/member")
            ->collect('entries')
            ->map($this->toMember(...))
            ->all());

        return $mailList;
    }

    /**
     * @return Collection<int, Fluent>
     *
     * @throws MailmanException
     */
    public function mailListsOf(string $member): Collection
    {
        // Mailman answers 404 when the member is on no list.
        $response = $this->request('post', '/lists/find', ['subscriber' => $member], fn (Response $response) => $response->notFound());

        return $response->notFound() ? collect() : $response->collect('entries')->map($this->toMailList(...));
    }

    /**
     * @throws MailmanException when the mail list already exists
     */
    public function createMailList(string $address): void
    {
        $this->request('post', '/lists', [
            'fqdn_listname' => $address.config('services.mailman.list_domain'),
        ]);
    }

    /** @throws MailmanException */
    public function deleteMailList(string $id): void
    {
        $this->request('delete', "/lists/$id");
    }

    /**
     * Removes the member from the mail list; a member who isn't on it (404) is fine.
     *
     * @throws MailmanException
     */
    public function removeMember(string $mailListId, string $memberEmail): void
    {
        $this->request('delete', "/lists/$mailListId/member/$memberEmail", isFine: fn (Response $response) => $response->notFound());
    }

    /**
     * Adds the member to the mail list; a member who is already on it (409) is fine.
     *
     * @throws MailmanException
     */
    public function addMember(string $mailListId, string $email, string $name): void
    {
        $this->request('post', '/members', [
            'list_id' => $mailListId,
            'subscriber' => $email,
            'display_name' => $name,
            'pre_verified' => true,
            'pre_confirmed' => true,
            'pre_approved' => true,
        ], fn (Response $response) => $response->conflict());
    }

    /** @throws MailmanException */
    public function removeFromAllMailLists(string $email): void
    {
        $this->mailListsOf($email)
            ->each(fn (Fluent $list) => $this->removeMember($list->get('id'), $email));
    }

    /**
     * Moves the subscriptions list by list, adding the new address before removing the old one, so a retry after a failure loses none.
     *
     * @throws MailmanException
     */
    public function changeEmailOnAllMailLists(string $name, string $oldEmail, string $newEmail): void
    {
        $this->mailListsOf($oldEmail)->each(function (Fluent $list) use ($name, $oldEmail, $newEmail) {
            $this->addMember($list->get('id'), $newEmail, $name);
            $this->removeMember($list->get('id'), $oldEmail);
        });
    }

    /**
     * Adds a member to the given mail lists that exist.
     *
     * @param  array<int, string>|Arrayable<int, string>  $mailListIds
     *
     * @throws MailmanException
     */
    public function addToMailLists(string $email, string $name, array|Arrayable $mailListIds): void
    {
        $this->existingMailLists($mailListIds, $email)->each(fn (string $listId) => $this->addMember($listId, $email, $name));
    }

    /**
     * Removes a member from the given mail lists that exist.
     *
     * @param  array<int, string>|Arrayable<int, string>  $mailListIds
     *
     * @throws MailmanException
     */
    public function removeFromMailLists(string $email, array|Arrayable $mailListIds): void
    {
        $this->existingMailLists($mailListIds, $email)->each(fn (string $listId) => $this->removeMember($listId, $email));
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

        $missing = $mailListIds->diff($this->mailListIds());
        if ($missing->isNotEmpty()) {
            Log::warning("Mail lists of $email don't exist in Mailman", ['list_ids' => $missing->all()]);
        }

        return $mailListIds->intersect($this->mailListIds());
    }
}

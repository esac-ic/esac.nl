<?php

namespace Tests\Concerns;

use Illuminate\Support\Facades\Config;

/**
 * Points the Mailman client at a fake host and builds Mailman REST API payloads.
 */
trait FakesMailman
{
    private string $mailmanUrl = 'http://mailman.test/3.1';

    protected function setUpFakesMailman(): void
    {
        Config::set('services.mailman.url', $this->mailmanUrl);
    }

    private function mailListPayload(string $name, int $memberCount = 0): array
    {
        return [
            'advertised' => 1,
            'display_name' => ucfirst($name),
            'fqdn_listname' => "$name@esac.nl",
            'list_id' => "$name.esac.nl",
            'list_name' => $name,
            'mail_host' => 'esac.nl',
            'member_count' => $memberCount,
            'volume' => 1,
            'description' => '',
            'self_link' => "http://localhost:8001/3.1/lists/$name.esac.nl",
            'http_etag' => sha1($name),
        ];
    }

    private function memberPayload(string $email, string $displayName, string $listId = 'lid.esac.nl'): array
    {
        $id = md5($email);

        return [
            'address' => "http://localhost:8001/3.1/addresses/$email",
            'bounce_score' => 0,
            'last_warning_sent' => '0001-01-01T00:00:00',
            'total_warnings_sent' => 0,
            'delivery_mode' => 'regular',
            'email' => $email,
            'list_id' => $listId,
            'subscription_mode' => 'as_address',
            'role' => 'member',
            'user' => "http://localhost:8001/3.1/users/$id",
            'display_name' => $displayName,
            'self_link' => "http://localhost:8001/3.1/members/$id",
            'member_id' => $id,
            'http_etag' => sha1($email),
        ];
    }

    /**
     * Mailman leaves out the entries key when a collection is empty.
     */
    private function collectionPayload(array $entries): array
    {
        return [
            'start' => 0,
            'total_size' => count($entries),
            ...($entries === [] ? [] : ['entries' => $entries]),
            'http_etag' => sha1(json_encode($entries)),
        ];
    }
}

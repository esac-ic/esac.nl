<?php
/**
 * Created by PhpStorm.
 * User: Niek
 * Date: 10-3-2019
 * Time: 18:41
 */

namespace App\CustomClasses\MailList;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class MailMan
{
    private string $_baseUrl;

    public function __construct()
    {
        $this->_baseUrl = Config::get('mailman.url');
    }

    protected function http(): PendingRequest
    {
        return Http::baseUrl($this->_baseUrl)->withBasicAuth(
            config('mailman.credentials.username'), config('mailman.credentials.password'),
        );
    }

    /**
     * @throws RequestException
     */
    public function get(string $url): Collection
    {
        return $this->http()->get($url)->throw()->collect();
    }

    /**
     * @throws RequestException
     */
    public function post(string $url, array $body): Collection
    {
        return $this->http()->asJson()->post($url, $body)->throw()->collect();
    }

    /**
     * @throws RequestException
     */
    public function delete(string $url, array $body = []): Collection
    {
        return $this->http()->asJson()->delete($url, $body)->throw()->collect();
    }
}

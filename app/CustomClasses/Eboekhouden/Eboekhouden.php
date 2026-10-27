<?php

namespace App\CustomClasses\Eboekhouden;

use App\Models\ApiToken;
use App\Repositories\Api;
use App\Repositories\ApiTokenRepository;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Eboekhouden
{
    private string $_baseUrl;
    private $_apiTokenRepository;

    public function __construct(ApiTokenRepository $apiTokenRepository)
    {
        $this->_baseUrl = Config::get('eboekhouden.url') ?? '';
        $this->_apiTokenRepository = $apiTokenRepository;
    }

    protected function startSession(): ApiToken
    {
        // Request an access token
        $response = Http::post($this->_baseUrl . '/session', [
            'accessToken' => Config::get('eboekhouden.api_token'),
            'source' => Config::get('eboekhouden.source'),
        ]);
        
        $response->throw();
        \Log::debug($response->json());
        
        // Store the token in the database
        if ($response->ok()) {
            $response = $response->json();
            // Subtract a couple of seconds of the expiry time to compensate for the RTT between us and eboekhouden
            return $this->_apiTokenRepository->set(
                Api::eboekhouden,
                $response['token'],
                $response['expiresIn'] - 5
            );
        }
        throw new Exception("Failed to retrieve an eboekhouden access token");
    }

    protected function getSession(): string
    {
        $token = $this->_apiTokenRepository->get(Api::eboekhouden);
        if($token === null) {
            $token = $this->startSession();
        }
        return $token->token;
    }

    protected function http(): PendingRequest
    {
        $sessionToken = $this->getSession();
        return Http::baseUrl($this->_baseUrl)->withHeader("Authorization", $sessionToken);
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
        if ($body){
            return $this->http()->asJson()->post($url, $body)->throw()->collect();
        } else {
            return $this->http()->post($url)->throw()->collect();
        }
    }

    /**
     * @throws RequestException
     */
    public function patch(string $url, array $body): Collection
    {
        if ($body){
            return $this->http()->asJson()->patch($url, $body)->throw()->collect();
        } else {
            return $this->http()->patch($url)->throw()->collect();
        }
    }

    /**
     * @throws RequestException
     */
    public function delete(string $url, array $body = []): Collection
    {
        if ($body){
            return $this->http()->asJson()->delete($url, $body)->throw()->collect();
        } else {
            return $this->http()->delete($url, $body)->throw()->collect();
        }
    }
}

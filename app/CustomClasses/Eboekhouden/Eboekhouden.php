<?php

namespace App\CustomClasses\Eboekhouden;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Eboekhouden
{
    private string $_baseUrl;
    
    public function __construct()
    {
        $this->_baseUrl = Config::get('eboekhouden.url') ?? '';
    }
    
    public function startSession(): string
    {
        Log::debug(Config::get('eboekhouden.source'));
        //make request to get access token
        $response = Http::post($this->_baseUrl . '/session', [
            'accessToken' => Config::get('eboekhouden.api_token'),
            'source' => Config::get('eboekhouden.source'),
        ]);
        
        $response->throw();
        \Log::debug($response->json());
        
        //store access token in session/local storage
        if ($response->ok()) {
            $token = $response->json()['token'];
            \Log::debug($token);
            \Log::debug($response->json()['expiresIn']);
            Session::put('eboekhouden.session_token', $token); //TODO not sure if this is secure
            return $token;
        }
        return "";
    }
    
    protected function http()
    {
//        $sessionToken = Session::get('eboekhouden.session_token');
//        if (empty($sessionToken)) {
//            $sessionToken = $this->startSession();
//        }
        $sessionToken = $this->startSession();
        //check if the token is expired
        return Http::baseUrl($this->_baseUrl)->withHeader("Authorization", $sessionToken);
    }
    
//    protected function http(): PendingRequest
//    {
//        return Http::baseUrl($this->_baseUrl)->withBasicAuth(
//            config('eboekhouden.credentials.username'), config('eboekhouden.credentials.password'),
//        );
//    }
    
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
    public function delete(string $url, array $body = []): Collection
    {
        if ($body){
            return $this->http()->asJson()->delete($url, $body)->throw()->collect();
        } else {
            return $this->http()->delete($url, $body)->throw()->collect();
        }
    }
}

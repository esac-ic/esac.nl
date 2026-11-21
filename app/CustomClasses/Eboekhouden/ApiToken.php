<?php

namespace App\CustomClasses\Eboekhouden;

use Illuminate\Support\Carbon;

class ApiToken
{
    private Carbon $expiresAt;
    public string $token;

    public function __construct(string $token, int $expiresIn)
    {
        $this->expiresAt = Carbon::now()->addSeconds($expiresIn);
        $this->token = $token;
    }

    public function isExpired(): bool
    {
        return $this->expiresAt < Carbon::now();
    }
}
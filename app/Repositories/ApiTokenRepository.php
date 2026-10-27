<?php

namespace App\Repositories;

use App\Models\ApiToken;
use Carbon\Carbon;

enum Api: int
{
    case eboekhouden = 0;
}

class ApiTokenRepository
{
    public function set(Api $id, string $token, int $expiresIn)
    {
        $expiresAt = Carbon::now()->addSeconds($expiresIn);
        return ApiToken::updateOrCreate(
            ['id'=>$id->value],
            ['token'=>$token, 'expires_at'=>$expiresAt]
        );
    }

    /**
     * Returns the api token with id (if a non expired token exists, else returns null)
     * @param Api $id
     * @return ApiToken|null
     */
    public function get(Api $id)
    {
        $token = ApiToken::where('id', '=', $id->value)->get()->first();

        if($token == null) return null;

        if($token->isExpired()) {
            $token->delete();
            return null;
        }
        return $token;
    }

}
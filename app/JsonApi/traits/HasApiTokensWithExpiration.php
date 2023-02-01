<?php

namespace App\JsonApi\Traits;

use DateTime;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Support\Str;

trait HasApiTokensWithExpiration
{
    use HasApiTokens;

    public function createToken(string $name, DateTime $expiredAt = null, $abilities = ['*'])
    {
        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => $abilities,
            'expired_at' => $expiredAt
        ]);

        return new NewAccessToken($token, $token->getKey() . '|' . $plainTextToken);
    }
}

<?php

declare(strict_types=1);

namespace JWT;
use JWT\Gnerator;

class Validator {

    protected static function token($token): bool
    {
        $JWTValidator = new Generator();
        return $JWTValidator->setToken($token)->validate() === Generator::TOKEN_VALID;
    }

}

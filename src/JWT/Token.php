<?php

declare(strict_types=1);

namespace JWT;
use \JWT\Generator;
use \JWT\Validator;
use \JWT\JWTAbstract;
use Core\Exceptions\UnauthorizedAccessException;

class Token extends Validator {

    private ?string $token;
    private array $payload;
    private $exp;
    private bool $hasValidated = false;

    public function __construct(
        string $token = null
        )
    {
        $this->token = $token;
        $this->exp = null;
    }

    public function get()
    {
        return $this->token;
    }

    public function set(
        string $token
        )
    {
        $this->token = $token;
        return $this;
    }

    public function payload(
        array $payload = null
        )
    {
        if (null!==$payload) {
            $this->payload = $payload;
            return $this;
        }

        if (!$this->hasValidated) $this->isValid();

        $parts = explode('.', $this->token);
        if (!isset($parts[1])) return [];
        return json_decode(base64_decode($parts[1]),TRUE);

    }



    public function isValid()
    {
        $isValid = Validator::token($this->token);
        if (!$isValid)
            throw new UnauthorizedAccessException(
                'Token provided is either expired or invalid'
            );
        return $isValid;
    }

    public function create()
    {
        $generate             = new Generator();
        $this->payload['exp'] = ((new \DateTime())->modify('+10 minutes')->getTimestamp());
        $this->token          = $generate->setPayload($this->payload)->generateToken();
        return $this->token;
    }

}

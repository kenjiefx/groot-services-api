<?php

namespace Core\Exceptions;

use Core\Exceptions\RocketExceptionsInterface;

class AlreadyExistsException extends \Exception implements RocketExceptionsInterface
{
    public function code()
    {
        return 409;
    }

    public function exception()
    {
        return 'Conflict';
    }
}

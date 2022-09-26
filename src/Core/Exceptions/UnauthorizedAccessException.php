<?php

namespace Core\Exceptions;

use Core\Exceptions\RocketExceptionsInterface;

class UnauthorizedAccessException extends \Exception implements RocketExceptionsInterface {

    public function code()
    {
        return 401;
    }

    public function exception()
    {
        return 'Unauthorized';
    }

}

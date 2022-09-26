<?php

namespace Core\Exceptions;

use Core\Exceptions\RocketExceptionsInterface;

class BadRequestException extends \Exception implements RocketExceptionsInterface
{
    public function code()
    {
        return 400;
    }

    public function exception()
    {
        return 'Bad Request';
    }
}

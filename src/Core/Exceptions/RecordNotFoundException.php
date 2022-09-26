<?php

namespace Core\Exceptions;

use Core\Exceptions\RocketExceptionsInterface;

class RecordNotFoundException extends \Exception implements RocketExceptionsInterface
{
    public function code()
    {
        return 404;
    }

    public function exception()
    {
        return 'Not Found';
    }
}

<?php

namespace Core\Exceptions;

use Core\Exceptions\RocketExceptionsInterface;

class ResourceAccessForbiddenException extends \Exception implements RocketExceptionsInterface {

    public function code()
    {
        return 403;
    }

    public function exception()
    {
        return 'Forbidden';
    }

}

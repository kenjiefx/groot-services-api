<?php

namespace Core\Exceptions;

use Core\Exceptions\RocketExceptionsInterface;

class ConfigurationErrorException extends \Exception implements RocketExceptionsInterface
{
    public function code()
    {
        return 500;
    }

    public function exception()
    {
        return 'Configuration Error';
    }
}

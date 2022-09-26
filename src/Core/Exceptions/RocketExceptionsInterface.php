<?php
declare(strict_types=1);
namespace Core\Exceptions;

interface RocketExceptionsInterface {

    public function code();
    public function exception();

}
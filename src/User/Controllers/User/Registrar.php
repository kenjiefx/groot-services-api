<?php

namespace User\Controllers\User;

use User\Models\User;
use Core\Exceptions\BadRequestException;
use Core\Exceptions\AlreadyExistsException;
use User\Adapters\RocketAdapter as DBAdapter;

class Registrar
{
    /**
     * Making sure that required User data is present 
     * @throws BadRequestException
     */
    public static function validate(
        User $User
        )
    {
        $fields = [
            $User->id(),
            $User->firstName(),
            $User->lastName(),
            $User->email(),
            $User->username(),
            $User->password(),
            $User->status()
        ];

        foreach ($fields as $field) 
            if (null===$field)
                throw new BadRequestException(
                    'User required field missing'
                );

    }

    public static function doExist(
        User $User
        )
    {
        $DBAdapter = (new DBAdapter('userdb'))->table('exists');

        # Checking whether the email address is already on record
        if ($DBAdapter->fileId($User->email())->doExist()) {
            throw new AlreadyExistsException(
                'User email already exists'
            );
        }

        # Checking whether the username is already on record
        if ($DBAdapter->fileId($User->username())->doExist()) {
            throw new AlreadyExistsException(
                'Username already exists'
            );
        }
    }

    
}

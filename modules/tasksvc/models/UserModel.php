<?php

declare(strict_types=1);
namespace tasksvc\models;
use \glyphic\TypeOf;
use \glyphic\TimeStamp;
use \glyphic\UniqueId;

class UserModel {

    private string $email;

    public function __construct(
        array $user
        )
    {
        $this->email = TypeOf::email('User email',$user['email']);
    }

    public function export()
    {
        return [
            'email' => $this->email
        ];
    }

    public function email()
    {
        return $this->email;
    }

}

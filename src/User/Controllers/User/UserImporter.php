<?php

namespace User\Controllers\User;

class UserImporter
{
    public function __construct(
        private User $User
        )
    {
        # constructor
    }

    public function import(
        array $raw
        )
    {
        $this->User->id($raw['id']);
        $this->User->tenantId($raw['tenantId']);
        $this->User->firstName($raw['firstName']);
        $this->User->lastName($raw['lastName']);
        $this->User->email($raw['email']);
        $this->User->password($raw['password']);
        $this->User->username($raw['username']);
        $this->User->profilePhoto($raw['profilePhoto']);
        $this->User->status(new Status($raw['status']));
        $this->User->permissions((new PermissionsImporter(new Permissions()))->import($raw['permissions']));
        $this->User->address($raw['address']);
        $this->User->createdAt(new TimeStamp($raw['createdAt']));
        $this->User->updatedAt(new TimeStamp($raw['updatedAt']));
        $this->User->contacts($raw['contacts']);
        return $this->User;
    }
}

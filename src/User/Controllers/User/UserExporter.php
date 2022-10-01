<?php

namespace User\Controllers\User;

class UserExporter
{
    public function __construct(
        private User $User
        )
    {
        # constructor
    }


    public function export()
    {
        return [
            'id' => $this->User->id(),
            'tenantId' => $this->User->tenantId(),
            'firstName' => $this->User->firstName(),
            'lastName' => $this->User->lastName(),
            'email' => $this->User->email(),
            'password' => $this->User->password(),
            'username' => $this->User->username(),
            'profilePhoto' => $this->User->profilePhoto(),
            'status' => (string) $this->User->status(),
            'permissions' => (new PermissionsExporter($this->User->permissions()))->export(),
            'address' => $this->User->address(),
            'createdAt' => (string) $this->User->createdAt(),
            'updatedAt' => (string) $this->User->updatedAt(),
            'contacts' => $this->User->contacts(),
        ];
    }
}

<?php

declare(strict_types=1);
namespace User\Controllers;

use Tools\TimeStamp;
use User\Models\User;
use User\Models\Status;
use User\Controllers\User\Registrar;
use User\Controllers\AddressController;
use User\Controllers\ContactsController;
use User\Controllers\PermissionsController;

class UserController {

    private AddressController $AddressController;
    private ContactsController $ContactsController;
    private PermissionsController $PermissionsController;
    private DBAdapter $DBAdapter;

    public function __construct(
        private User $User
        )
    {
    }

    public function create()
    {
        # User status must be new when creating user 
        $this->User->status(Status::new());

        # Setting updated at
        $this->User->updatedAt(
            $this->User->status()->createdAt()
        );
        
        # Validating required user data fields
        Registrar::validate($this->User);

        # Checking whether the email address and username is already on record
        Registrar::doExist($this->User);

        # Registrar::save($this->User);

    }

    public function update()
    {
    }





    

}

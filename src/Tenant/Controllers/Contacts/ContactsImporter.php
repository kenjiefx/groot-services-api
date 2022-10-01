<?php

namespace Tenant\Controllers\Contacts;

use Tools\TimeStamp;
use Tenant\Models\Contacts;

class ContactsImporter
{
    public function __construct(
        private Contacts $Contacts
        )
    {

    }

    public function import(
        array $raw
        )
    {   
        $this->Contacts->id($raw['id']);
        $this->Contacts->type($raw['type']);
        $this->Contacts->mobileNumber($raw['mobileNumber']);
        $this->Contacts->telephoneNumber($raw['telephoneNumber']);
        $this->Contacts->emailAddress($raw['emailAddress']);
        $this->Contacts->faxNumber($raw['faxNumber']);
        $this->Contacts->createdAt((new TimeStamp())->timeStamp($raw['createdAt']));
        $this->Contacts->updatedAt((new TimeStamp())->timeStamp($raw['updatedAt']));
        return $this->Contacts;
    }
}

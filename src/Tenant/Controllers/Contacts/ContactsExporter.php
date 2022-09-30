<?php

namespace Tenant\Controllers\Contacts;

use Tenant\Models\Contacts;

class ContactsExporter {

    public function __construct(
        private Contacts $Contacts
        )
    {

    }

    public function export()
    {
        return [
            'id' => $this->Contacts->id(),
            'type' => $this->Contacts->type(),
            'mobileNumber' => $this->Contacts->mobileNumber(),
            'telephoneNumber' => $this->Contacts->telephoneNumber(),
            'emailAddress' => $this->Contacts->emailAddress(),
            'faxNumber' => $this->Contacts->faxNumber(),
            'createdAt' => (string) $this->Contacts->createdAt(),
            'updatedAt' => (string) $this->Contacts->updatedAt()
        ];
    }

}

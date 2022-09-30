<?php

namespace Tenant\Controllers\Business;

use Tenant\Models\Address;
use Tenant\Models\Business;
use Tenant\Controllers\Address\AddressExporter;
use Tenant\Controllers\Contacts\ContactsExporter;
use Tenant\Controllers\Owner\OwnerExporter;

class BusinessExporter
{
    public function __construct(
        private Business $Business
        )
    {

    }

    public function export()
    {
        return [
            'id' => $this->Business->id(),
            'name' => $this->Business->name(),
            'address' => (new AddressExporter($this->Business->address()))->export(),
            'contacts' => (new ContactsExporter($this->Business->contacts()))->export(),
            'owner' => (new OwnerExporter($this->Business->owner()))->export(),
            'industry' => (string) $this->Business->industry(),
            'taxId' => $this->Business->taxId(),
            'registrationNo' => $this->Business->registrationNo()
        ];
    }
}

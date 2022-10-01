<?php

namespace Tenant\Controllers\Business;

use Tenant\Models\Address;
use Tenant\Models\Business;
use Tenant\Models\Contacts;
use Tenant\Models\Industry;
use Tenant\Controllers\Address\AddressImporter;
use Tenant\Controllers\Contacts\ContactsImporter;

class BusinessImporter
{
    public function __construct(
        private Business $Business
        )
    {

    }

    public function import(
        array $raw
        )
    {
        $industry = $raw['industry'] ?? 'General';
        $this->Business->id($raw['id']);
        $this->Business->name($raw['name']);
        $this->Business->address((new AddressImporter(new Address()))->import($raw['address']));
        $this->Business->contacts((new ContactsImporter(new Contacts()))->import($raw['contacts']));
        $this->Business->industry(Industry::$industry());
        $this->Business->taxId($raw['taxId']);
        $this->Business->registrationNo($raw['registrationNo']);
        return $this->Business;
    }


}

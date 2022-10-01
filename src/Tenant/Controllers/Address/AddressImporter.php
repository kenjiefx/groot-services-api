<?php

namespace Tenant\Controllers\Address;

use Tools\TimeStamp;
use Tenant\Models\Address;

class AddressImporter
{
    public function __construct(
        private Address $Address
        )
    {

    }

    public function import(
        array $raw
        )
    {   
        $this->Address->id($raw['id']);
        $this->Address->type($raw['type']);
        $this->Address->street($raw['street']);
        $this->Address->house($raw['house']);
        $this->Address->building($raw['building']);
        $this->Address->floor($raw['floor']);
        $this->Address->room($raw['room']);
        $this->Address->zone($raw['zone']);
        $this->Address->barangay($raw['barangay']);
        $this->Address->town($raw['town']);
        $this->Address->city($raw['city']);
        $this->Address->province($raw['province']);
        $this->Address->region($raw['region']);
        $this->Address->state($raw['state']);
        $this->Address->country($raw['country']);
        $this->Address->zipcode($raw['zipcode']);
        $this->Address->createdAt((new TimeStamp())->timeStamp($raw['createdAt']));
        $this->Address->updatedAt((new TimeStamp())->timeStamp($raw['updatedAt']));
        return $this->Address;
    }
}

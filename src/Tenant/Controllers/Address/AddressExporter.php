<?php

namespace Tenant\Controllers\Address;

use Tenant\Models\Address;

class AddressExporter
{
    public function __construct(
        private Address $Address
        )
    {

    }

    public function export()
    {
        return [
            'id' => $this->Address->id(),
            'type' => $this->Address->type(),
            'street' => $this->Address->street(),
            'house' => $this->Address->house(),
            'building' => $this->Address->building(),
            'floor' => $this->Address->floor(),
            'room' => $this->Address->room(),
            'zone' => $this->Address->zone(),
            'barangay' => $this->Address->barangay(),
            'town' => $this->Address->town(),
            'city' => $this->Address->city(),
            'province' => $this->Address->province(),
            'region' => $this->Address->region(),
            'state' => $this->Address->state(),
            'country' => $this->Address->country(),
            'zipcode' => $this->Address->zipcode(),
            'createdAt' => (string) $this->Address->createdAt(),
            'updatedAt' => (string) $this->Address->updatedAt()
        ];
    }
}

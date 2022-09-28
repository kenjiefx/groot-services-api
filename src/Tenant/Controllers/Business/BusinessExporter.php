<?php

namespace Tenant\Controllers\Business;

use Tenant\Models\Address;
use Tenant\Models\Business;
use Tenant\Controllers\Address\AddressExporter;

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
            'address' => (new AddressExporter($this->Business->address()))->export()
        ];
    }
}

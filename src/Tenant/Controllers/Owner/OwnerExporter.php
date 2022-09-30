<?php

namespace Tenant\Controllers\Owner;

use Tenant\Models\Owner;

class OwnerExporter {

    public function __construct(
        private Owner $Owner
        )
    {

    }

    public function export()
    {
        return [
            'firstName' => $this->Owner->firstName(),
            'lastName' => $this->Owner->lastName(),
            'email' => $this->Owner->email()
        ];
    }

}

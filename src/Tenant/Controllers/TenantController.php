<?php

namespace Tenant\Controllers;

use Tools\UniqueId;
use Tenant\Models\Tenant;
use Tenant\Controllers\Tenant\TenantFactory;

class TenantController
{
    public function __construct(
        private Tenant $Tenant
        )
    {

    }

    public function create()
    {
        $TenantFactory = new TenantFactory(
            $this->Tenant
        );
        return $TenantFactory;
    }
}

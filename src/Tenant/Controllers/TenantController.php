<?php

namespace Tenant\Controllers;

use Tools\UniqueId;
use Tenant\Models\Tenant;
use Tenant\Controllers\Tenant\TenantAdapter;
use Tenant\Controllers\Tenant\TenantFactory;
use Tenant\Controllers\Tenant\TenantsDBAdapter;

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
        $TenantAdapter = new TenantAdapter(
            $this->Tenant
        );
        $TenantsDB = new TenantsDBAdapter();
        $TenantsDB->addTenant(
            $this->Tenant
        );
        return $TenantAdapter->generate();
    }


}

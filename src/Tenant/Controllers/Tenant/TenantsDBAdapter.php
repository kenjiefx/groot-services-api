<?php

namespace Tenant\Controllers\Tenant;

use Rocket\RocketDB;
use Tenant\Models\Tenant;
use Tenant\Controllers\Tenant\TenantExporter;

class TenantsDBAdapter
{

    private RocketDB $RocketDB;

    public function __construct()
    {
        $this->RocketDB = new RocketDB(
            'tenantsdb'
        );    
    }

    public function addTenant(
        Tenant $Tenant
        )
    {
        $exporter = new TenantExporter($Tenant);
        $collection = $this->RocketDB->collection('tenants');
        $collection->document($Tenant->publicKey())
                   ->create(json_encode($exporter->export()));
        $this->indexTenant($Tenant);
    }

    public function indexTenant(
        Tenant $Tenant
        )
    {
        $collection = $this->RocketDB->collection('tenants');
        $collection->index('industry')
                   ->key($Tenant->business()->industry())
                   ->value($Tenant->publicKey())
                   ->create();
        $collection->index('country')
                   ->key($Tenant->business()->address()->country())
                   ->value($Tenant->publicKey())
                   ->create();
    }





}

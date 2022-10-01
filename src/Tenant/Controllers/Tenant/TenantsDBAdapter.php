<?php

namespace Tenant\Controllers\Tenant;

use Rocket\RocketDB;
use Tenant\Models\Tenant;
use Tenant\Controllers\Tenant\TenantExporter;

class TenantsDBAdapter
{

    private RocketDB $RocketDB;

    private array $entities = [
        'industry', 'country'
    ];

    private ?array $memoized = null;

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
        $this->clearDBCache(['--ALL'=>'--ALL']);
    }

    public function updateTenant(
        Tenant $Tenant
        )
    {
        $exporter = new TenantExporter($Tenant);
        $collection = $this->RocketDB->collection('tenants');
        $collection->document($Tenant->publicKey())
                   ->create(json_encode($exporter->export()));
        $this->indexTenant($Tenant);
        $this->clearDBCache(['--ALL'=>'--ALL']);
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

    public function clearDBCache(
        array $schema
        )
    {
        if (isset($schema['--ALL'])) {
            $staged = [];
            foreach ($this->entities as $entity) 
                $staged[$entity] = $schema['--ALL'];
        } else {
            $staged = $schema;
        }
        foreach ($staged as $key => $value) {
            if (in_array($key,$this->entities)) 
            $this->RocketDB->cache($key)->clear($value);
        }
    }

    public function doExist(
        Tenant $Tenant
        )
    {
        $collection = $this->RocketDB->collection('tenants');
        $data = $collection->document($Tenant->publicKey())->get();
        if (null===$data) return false;
        $this->memoized = json_decode($data,TRUE);
        return true;
    }

    public function getTenant(
        ?Tenant $Tenant = null
        )
    {
        if (null!==$this->memoized) return $this->memoized;
        if (null===$Tenant) return [];
        $data = $this->RocketDB
                     ->collection('tenants')
                     ->document($Tenant->publicKey())
                     ->get();
        return json_decode($data,true);

    }





}

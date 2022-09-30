<?php

namespace Tenant\Controllers\Tenant;

use Rocket\RocketDB;
use Tenant\Models\Tenant;

class TenantAdapter
{

    private RocketDB $RocketDB;

    public function __construct(
        private Tenant $Tenant
        )
    {

    }

    public function save()
    {

    }

    public function generate()
    {
        $RocketDB = new RocketDB(
            $this->Tenant->id()
        );
        $RocketDB->create();
        $RocketDB->collection('users')->create();
        $RocketDB->collection('posts')->create();
        $RocketDB->collection('tasks')->create();
    }

}

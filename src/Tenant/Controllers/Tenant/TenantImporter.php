<?php

namespace Tenant\Controllers\Tenant;

use Tools\TimeStamp;
use User\Models\User;
use Tenant\Models\Status;
use Tenant\Models\Tenant;
use Tenant\Models\Business;
use Tenant\Controllers\Business\BusinessImporter;

class TenantImporter
{
    public function __construct(
        private Tenant $Tenant
        )
    {

    }

    public function import(
        array $raw
        )
    {
        $status = $raw['status'] ?? 'banned';
        $this->Tenant->id($raw['id']);
        $this->Tenant->publicKey($raw['publicKey']);
        $this->Tenant->secretKey($raw['secretKey']);
        $this->Tenant->createdAt((new TimeStamp())->timeStamp($raw['createdAt']));
        $this->Tenant->updatedAt((new TimeStamp())->timeStamp($raw['updatedAt']));
        $this->Tenant->status(Status::$status());
        $this->Tenant->business((new BusinessImporter(new Business()))->import($raw['business']));
        $this->Tenant->admin((new User()));
        $this->Tenant->teammates($raw['teammates']);
        
        return $this->Tenant;
    }




}

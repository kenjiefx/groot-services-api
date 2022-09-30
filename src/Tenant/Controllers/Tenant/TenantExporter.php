<?php

namespace Tenant\Controllers\Tenant;

use Tenant\Models\Tenant;
use Tenant\Controllers\Business\BusinessExporter;

class TenantExporter
{
    public function __construct(
        private Tenant $Tenant
        )
    {

    }

    public function export()
    {
        return [
            'id' => $this->Tenant->id(),
            'publicKey' => $this->Tenant->publicKey(),
            'secretKey' => $this->Tenant->secretKey(),
            'createdAt' => (string) $this->Tenant->createdAt(),
            'updatedAt' => (string) $this->Tenant->updatedAt(),
            'status' => (string) $this->Tenant->status(),
            'business' => (new BusinessExporter($this->Tenant->business()))->export(),
            'admin' => $this->Tenant->admin() ?? null,
            'teammates' => $this->Tenant->teammates ?? null
        ];
    }
}

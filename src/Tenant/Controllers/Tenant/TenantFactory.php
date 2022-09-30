<?php

namespace Tenant\Controllers\Tenant;

use Tools\UniqueId;
use User\Models\User;
use Tenant\Models\Status;
use Tenant\Models\Tenant;
use Tenant\Models\Business;
use Tenant\Settings\Settings;

class TenantFactory
{
    public function __construct(
        private Tenant $Tenant
        )
    {
        $this->Tenant->publicKey(
            UniqueId::create32bitKey(
                UniqueId::ALPHANUMERIC
            )
        );
        $this->Tenant->secretKey(
            UniqueId::create32bitKey(
                UniqueId::ALPHANUMERIC
            )
        );
        $this->Tenant->status(
            Status::new()
        );
        $this->Tenant->business(
            new Business()
        );
        $this->Tenant->settings(
            new Settings()
        );
        $this->Tenant->admin(null);
        $this->Tenant->teammates(null);

    }


}

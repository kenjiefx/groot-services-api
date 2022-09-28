<?php

declare(strict_types=1);
namespace Tenant\Models;

use Core\Exceptions\UnauthorizedAccessException;

class Requester {

    public ?string $userId;
    public ?string $tenantId;
    public ?string $userStatus;
    public ?array $permissions;
    private array $dataFields = ['userId','tenantId','userStatus','permissions'];

    public function __construct(
        array $requester
        )
    {
        $this->parse($requester);
    }

    private function parse(
        array $requester
        )
    {
        foreach ($this->dataFields as $dataField) {
            if (!isset($requester[$dataField])) {
                throw new UnauthorizedAccessException(
                    'Invalid token Requester payload: '.$dataField
                );
            }
            $this->$dataField =  $requester[$dataField];
        }
    }

    public function export()
    {
        return [
            'userId' => $this->userId,
            'tenantId' => $this->tenantId,
            'userStatus' => $this->userStatus,
            'permissions' => $this->permissions
        ];
    }



}

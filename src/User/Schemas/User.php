<?php

namespace User\Schemas;

class User
{
    private static array $schema = [
        'id' => [
            'type' => 'string',
            'readonly' => true
        ],
        'tenantId' => [
            'type' => 'string',
            'readonly' => true
        ],
        'firstName' => [
            'type' => 'string'
        ],
        'lastName' => [
            'type' => 'string'
        ],
        'email' => [
            'type' => 'string'
        ],
        'password' => [
            'type' => 'string'
        ],
        'username' => [
            'type' => 'string'
        ],
        'profilePhoto' => [
            'type' => 'string'
        ],
        'status' => [
            'type' => 'Status',
            'export' => '(string)'
        ],
        'permissions' => [
            'type' => 'Permissions',
            'export' => 'export()'
        ],
        'address' => [
            'type' => 'array'
        ],
        'createdAt' => [
            'type' => 'TimeStamp',
            'export' => '(string)'
        ],
        'updatedAt' => [
            'type' => 'TimeStamp',
            'export' => '(string)'
        ],
        'contacts' => [
            'type' => 'array'
        ]
    ];

    public static function getSchema()
    {
        return Self::$schema;
    }


}

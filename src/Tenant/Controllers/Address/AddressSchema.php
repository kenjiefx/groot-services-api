<?php

namespace Tenant\Controllers\Address;

class AddressSchema
{

    private static array $schema = [
        'id' => [
            'type' => 'string',
            'readonly' => true
        ],
        'type' => [
            'type' => 'string'
        ],
        'street' => [
            'type' => 'string'
        ],
        'house' => [
            'type' => 'string'
        ],
        'building' => [
            'type' => 'string'
        ],
        'floor' => [
            'type' => 'string'
        ],
        'room' => [
            'type' => 'string'
        ],
        'zone' => [
            'type' => 'string'
        ],
        'barangay' => [
            'type' => 'string'
        ],
        'town' => [
            'type' => 'string'
        ],
        'city' => [
            'type' => 'string'
        ],
        'province' => [
            'type' => 'string'
        ],
        'region' => [
            'type' => 'string'
        ],
        'state' => [
            'type' => 'string'
        ],
        'country' => [
            'type' => 'string'
        ],
        'zipcode' => [
            'type' => 'string'
        ],
        'createdAt' => [
            'type' => 'TimeStamp',
            'readonly' => true
        ],
        'house' => [
            'type' => 'string',
            'readonly' => true
        ],
    ];

    public static function isReadonly(
        string $key
        )
    {
        if (!isset(Self::$schema[$key])) return false;
        if (!isset(Self::$schema[$key]['readonly'])) return true;
        return !Self::$schema[$key]['readonly'];
    }

}

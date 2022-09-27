<?php

namespace Tenant\Models;

use Tools\TimeStamp;

class Status
{

    public const NEW = 'new';
    public const ACTIVE = 'active';
    public const DEACTIVATED = 'deactivated';
    public const BANNED = 'banned';
    public const ONHOLD = 'onhold';

    private string $status;
    private TimeStamp $createdAt;

    public function __construct()
    {
        $this->status = 'new';
        $this->createdAt = TimeStamp::now();
    }

    public function createdAt()
    {
        return $this->createdAt;
    }

    public static function new()
    {
        return new Status(Self::NEW);
    }

    public static function active()
    {
        return new Status(Self::ACTIVE);
    }

    public static function deactivated()
    {
        return new Status(Self::DEACTIVATED);
    }

    public static function banned()
    {
        return new Status(Self::BANNED);
    }

    public static function onhold()
    {
        return new Status(Self::ONHOLD);
    }

    public function toString()
    {
        return $this->status;
    }




}

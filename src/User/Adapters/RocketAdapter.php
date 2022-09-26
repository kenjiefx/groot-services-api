<?php

namespace User\Adapters;

use Rocket\RocketDB;

class RocketAdapter
{

    private RocketDB $RocketDB;
    private string $table;
    private string $fileId;

    public function __construct(
        private string $dbName
        )
    {
        $this->RocketDB = new RocketDB;
    }

    public function table(
        string $table
        )
    {
        $this->table = $table;
        return $this;
    }

    public function fileId(
        string $fileId
        )
    {
        $this->fileId = $fileId;
        return $this;
    }

    public function doExist()
    {
        return true;
    }

}

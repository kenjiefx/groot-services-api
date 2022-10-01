<?php

namespace Rocket;

use Rocket\RocketDB;
use Rocket\RocketCollection;

class RocketDocument
{
    public function __construct(
        private string $name,
        private RocketCollection $RocketCollection,
        private RocketDB $RocketDB
        )
    {

    }

    public function create(
        string $contents
        )
    {
        file_put_contents($this->getFilePath(),$contents);
    }

    public function get()
    {
        if (file_exists($this->getFilePath()))
            return file_get_contents($this->getFilePath());
        return null;
    }

    private function getFilePath()
    {
        return $this->RocketDB->getDBPath().
               $this->RocketCollection->getName().
               RocketDB::DATA.
               $this->name;
    }

}

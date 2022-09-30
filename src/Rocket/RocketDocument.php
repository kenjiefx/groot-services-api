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

    private function getFilePath()
    {
        return $this->RocketDB->getDBPath().
               $this->RocketCollection->getName().
               RocketDB::DATA.
               $this->name;
    }
}

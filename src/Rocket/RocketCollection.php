<?php

namespace Rocket;

use Rocket\RocketDB;
use Rocket\RocketIndex;

class RocketCollection
{
    public function __construct(
        private string $name,
        private RocketDB $RocketDB
        )
    {

    }

    public function document(
        string $fileName
        )
    {
        return new RocketDocument(
            $fileName,$this,$this->RocketDB
        );       
    }

    public function index(
        string $indexName
        )
    {
        return new RocketIndex(
            $indexName,$this,$this->RocketDB
        );
    }

    public function getName()
    {
        return $this->name;
    }

    public function create()
    {
        mkdir($this->getPath());
        mkdir($this->getPath().RocketDB::INDEX);
        mkdir($this->getPath().RocketDB::DATA);
    }

    public function getPath()
    {
        return $this->RocketDB->getDBPath().$this->name.'/';
    }

}

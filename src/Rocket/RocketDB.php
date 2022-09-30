<?php

namespace Rocket;

use Rocket\RocketCollection;

class RocketDB
{

    public const DIR = '/data/';
    public const INDEX = '/_index/';
    public const DATA = '/_data/';


    public function __construct(
        private string $dbName
        )
    {
        
    }

    public function create()
    {
        mkdir(ROOT.RocketDB::DIR.$this->dbName);
    }

    public function collection(
        string $name
        )
    {
        return new RocketCollection($name,$this);
    }

    public function getDBPath()
    {
        return ROOT.RocketDB::DIR.$this->dbName.'/';
    }


}

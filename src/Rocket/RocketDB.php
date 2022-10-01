<?php

namespace Rocket;

use Rocket\RocketCache;
use Rocket\RocketCollection;

class RocketDB
{

    public const DIR = '/data/';
    public const CACHE = '/cache/';
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
        mkdir(ROOT.RocketDB::CACHE.$this->dbName);
    }

    public function collection(
        string $name
        )
    {
        return new RocketCollection($name,$this);
    }

    public function cache(
        string $name
        )
    {
        return new RocketCache($name,$this);
    }

    public function getDBPath()
    {
        return ROOT.RocketDB::DIR.$this->dbName.'/';
    }

    public function getCachePath()
    {
        return ROOT.RocketDB::CACHE.$this->dbName.'/';
    }


}

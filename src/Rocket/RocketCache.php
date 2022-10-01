<?php

namespace Rocket;

use Rocket\RocketDB;

class RocketCache
{
    public function __construct(
        private string $name,
        private RocketDB $RocketDB
        )
    {

    }

    public function clear(
        ?string $fileName = null
        )
    {
        $this->initialize();
        if (null===$fileName)
            $fileName = 'null';
        if ($fileName==='--ALL')
            return $this->clearDir();
    }

    private function initialize()
    {
        if (!is_dir($this->getPath()))
            mkdir($this->getPath());
    }

    public function getPath()
    {
        return $this->RocketDB->getCachePath().$this->name;
    }

    private function clearDir()
    {
        foreach(scandir($this->getPath()) as $file) {
            if ($file==='.'||$file==='..') continue;
            $path = $this->getPath().'/'.$file;
            unlink($path);
        }
    }
}

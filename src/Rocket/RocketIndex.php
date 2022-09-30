<?php

namespace Rocket;

class RocketIndex
{

    private ?string $indexKey = null;
    private ?string $indexValue = null;

    public function __construct(
        private string $name,
        private RocketCollection $RocketCollection,
        private RocketDB $RocketDB
        )
    {

    }


    public function key(
        ?string $key = null
        )
    {
        $this->key = $key ?? 'null';
        return $this;
    }

    public function value(
        ?string $value = null
        )
    {
        $this->value = $value;
        return $this;
    }

    public function create(
        ?string $content = null
        )
    {
        $this->initialize();
        $this->clear();
        $filePath = $this->getKeyPath().'/'.$this->value;
        file_put_contents($filePath,$content??'');
    }

    private function initialize()
    {
        if (!is_dir($this->getPath()))
            mkdir($this->getPath());
        if (!is_dir($this->getKeyPath()))
            mkdir($this->getKeyPath());
        return $this;
    }

    private function clear()
    {
        foreach(scandir($this->getPath()) as $key)
        {
            if ($key==='.'||$key==='..') continue;
            $file = $this->getPath().'/'.$key.'/'.$this->value;
            if (null!==$this->value)
                if (file_exists($file)) 
                    unlink($file);
        }
    }


    public function getPath()
    {
        return $this->RocketDB->getDBPath().
               $this->RocketCollection->getName().
               RocketDB::INDEX.
               $this->name;
    }

    public function getKeyPath()
    {
        return $this->getPath().'/'.$this->key;
    }
}

<?php

declare(strict_types=1);
namespace Tools;

class TimeStamp
{

    private string $timeStamp = '';

    public function __construct()
    {

    }

    public function timeStamp(
        string $timeStamp
        )
    {
        $this->timeStamp = $timeStamp;
        return $this;
    }

    # Returns the current timestamp in the time zone
    public static function now ()
    {
        $date = new \DateTime(
            "now",
            new \DateTimeZone("Asia/Manila")
        );
        $timeStamp = new TimeStamp();
        $timeStamp->timeStamp($date->format('c'));
        return $timeStamp;
    }

    public static function add (
        string $date,
        string $addParam
        )
    {
        return (new \DateTime($date))
                ->modify("+{$addParam}")
                ->format('c');
    }

    public function __toString()
    {
        return $this->timeStamp;
    }
}

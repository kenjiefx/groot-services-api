<?php

namespace Tenant\Models;

class Industry {

    private string $industry;

    public function __construct(
        string $industry
        )
    {
        $this->industry = $industry;
    }

    public static function General()
    {
        return new Industry('general');
    }

    public function __toString()
    {
        return $this->industry;
    }

}

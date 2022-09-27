<?php

namespace Tenant\Models;

class Owner
{
    private string $firstName;
    private string $lastName;
    private string $email;


    public function __construct(
        
        )
    {
        # constructor
    }


    public function firstName (
        ?string $firstName = null
        )
    {
        if(null===$firstName){
            return (isset($this->firstName)) ?
                $this->firstName : null;
        }
        $this->firstName = $firstName;
        return $this;
    }


    public function lastName (
        ?string $lastName = null
        )
    {
        if(null===$lastName){
            return (isset($this->lastName)) ?
                $this->lastName : null;
        }
        $this->lastName = $lastName;
        return $this;
    }


    public function email (
        ?string $email = null
        )
    {
        if(null===$email){
            return (isset($this->email)) ?
                $this->email : null;
        }
        $this->email = $email;
        return $this;
    }
}

<?php

declare(strict_types=1);
namespace Tenant\Models;

use Tenant\Models\Address;
use Tenant\Models\Contacts;

class Business {

    private string $id;
    private string $name;
    private Address $address;
    private Contacts $contacts;
    private Owner $owner;
    private Industry $industry;
    private string $taxId;
    private string $registrationNo;


    public function __construct(

        )
    {
        $this->id = UniqueId::create32bitKey(
            UniqueId::BETANUMERIC
        );
    }


    public function id (
        ?string $id = null
        )
    {
        if(null===$id){
            return (isset($this->id)) ?
                $this->id : null;
        }
        $this->id = $id;
        return $this;
    }


    public function name (
        ?string $name = null
        )
    {
        if(null===$name){
            return (isset($this->name)) ?
                $this->name : null;
        }
        $this->name = $name;
        return $this;
    }


    public function address (
        ?Address $address = null
        )
    {
        if(null===$address){
            return (isset($this->address)) ?
                $this->address : null;
        }
        $this->address = $address;
        return $this;
    }


    public function contacts (
        ?Contacts $contacts = null
        )
    {
        if(null===$contacts){
            return (isset($this->contacts)) ?
                $this->contacts : null;
        }
        $this->contacts = $contacts;
        return $this;
    }


    public function owner (
        ?Owner $owner = null
        )
    {
        if(null===$owner){
            return (isset($this->owner)) ?
                $this->owner : null;
        }
        $this->owner = $owner;
        return $this;
    }


    public function industry (
        ?Industry $industry = null
        )
    {
        if(null===$industry){
            return (isset($this->industry)) ?
                $this->industry : null;
        }
        $this->industry = $industry;
        return $this;
    }


    public function taxId (
        ?string $taxId = null
        )
    {
        if(null===$taxId){
            return (isset($this->taxId)) ?
                $this->taxId : null;
        }
        $this->taxId = $taxId;
        return $this;
    }


    public function registrationNo (
        ?string $registrationNo = null
        )
    {
        if(null===$registrationNo){
            return (isset($this->registrationNo)) ?
                $this->registrationNo : null;
        }
        $this->registrationNo = $registrationNo;
        return $this;
    }
}

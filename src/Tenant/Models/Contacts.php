<?php

namespace Tenant\Models;

use Tools\UniqueId;

class Contacts
{
    private string $id;
    private string $type;
    private string $mobileNumber;
    private string $telephoneNumber;
    private string $emailAddress;
    private string $faxNumber;
    private TimeStamp $createdAt;
    private TimeStamp $updatedAt;


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


    public function type (
        ?string $type = null
        )
    {
        if(null===$type){
            return (isset($this->type)) ?
                $this->type : null;
        }
        $this->type = $type;
        return $this;
    }


    public function mobileNumber (
        ?string $mobileNumber = null
        )
    {
        if(null===$mobileNumber){
            return (isset($this->mobileNumber)) ?
                $this->mobileNumber : null;
        }
        $this->mobileNumber = $mobileNumber;
        return $this;
    }


    public function telephoneNumber (
        ?string $telephoneNumber = null
        )
    {
        if(null===$telephoneNumber){
            return (isset($this->telephoneNumber)) ?
                $this->telephoneNumber : null;
        }
        $this->telephoneNumber = $telephoneNumber;
        return $this;
    }


    public function emailAddress (
        ?string $emailAddress = null
        )
    {
        if(null===$emailAddress){
            return (isset($this->emailAddress)) ?
                $this->emailAddress : null;
        }
        $this->emailAddress = $emailAddress;
        return $this;
    }


    public function faxNumber (
        ?string $faxNumber = null
        )
    {
        if(null===$faxNumber){
            return (isset($this->faxNumber)) ?
                $this->faxNumber : null;
        }
        $this->faxNumber = $faxNumber;
        return $this;
    }


    public function createdAt (
        ?TimeStamp $createdAt = null
        )
    {
        if(null===$createdAt){
            return (isset($this->createdAt)) ?
                $this->createdAt : null;
        }
        $this->createdAt = $createdAt;
        return $this;
    }


    public function updatedAt (
        ?TimeStamp $updatedAt = null
        )
    {
        if(null===$updatedAt){
            return (isset($this->updatedAt)) ?
                $this->updatedAt : null;
        }
        $this->updatedAt = $updatedAt;
        return $this;
    }
}

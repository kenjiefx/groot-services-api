<?php

declare(strict_types=1);
namespace User\Models;

use Tools\UniqueId;
use Tools\TimeStamp;
use User\Models\Address;
use User\Models\Contacts;
use User\Models\Permissions;

class User {

    private string $id;
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $password;
    private string $username;
    private Status $status;
    private Permissions $permissions;
    private Address $address;
    private TimeStamp $createdAt;
    private TimeStamp $updatedAt;
    private Contacts $contacts;


    public function __construct(
        
        )
    {
        $this->id = UniqueId::create32bitKey(
            UniqueId::BETANUMERIC
        );
        $this->createdAt = TimeStamp::now();
        $this->updatedAt = TimeStamp::now();
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


    public function password (
        ?string $password = null
        )
    {
        if(null===$password){
            return (isset($this->password)) ?
                $this->password : null;
        }
        $this->password = $password;
        return $this;
    }


    public function username (
        ?string $username = null
        )
    {
        if(null===$username){
            return (isset($this->username)) ?
                $this->username : null;
        }
        $this->username = $username;
        return $this;
    }


    public function status (
        ?Status $status = null
        )
    {
        if(null===$status){
            return (isset($this->status)) ?
                $this->status : null;
        }
        $this->status = $status;
        return $this;
    }


    public function permissions (
        ?Permissions $permissions = null
        )
    {
        if(null===$permissions){
            return (isset($this->permissions)) ?
                $this->permissions : null;
        }
        $this->permissions = $permissions;
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

}

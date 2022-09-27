<?php

declare(strict_types=1);
namespace Tenant\Models;

use Tools\UniqueId;
use Tools\TimeStamp;
use User\Models\User;
use Tenant\Models\Status;
use Tenant\Models\Business;
use Tenant\Settings\Settings;

class Tenant {

    private string $id;
    private string $publicKey;
    private string $secretKey;
    private TimeStamp $createdAt;
    private TimeStamp $updatedAt;
    private Status $status;
    private Business $business;
    private User $admin;
    private array $teammates;
    private Settings $settings;


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


    public function publicKey (
        ?string $publicKey = null
        )
    {
        if(null===$publicKey){
            return (isset($this->publicKey)) ?
                $this->publicKey : null;
        }
        $this->publicKey = $publicKey;
        return $this;
    }


    public function secretKey (
        ?string $secretKey = null
        )
    {
        if(null===$secretKey){
            return (isset($this->secretKey)) ?
                $this->secretKey : null;
        }
        $this->secretKey = $secretKey;
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


    public function business (
        ?Business $business = null
        )
    {
        if(null===$business){
            return (isset($this->business)) ?
                $this->business : null;
        }
        $this->business = $business;
        return $this;
    }


    public function admin (
        ?User $admin = null
        )
    {
        if(null===$admin){
            return (isset($this->admin)) ?
                $this->admin : null;
        }
        $this->admin = $admin;
        return $this;
    }


    public function teammates (
        ?array $teammates = null
        )
    {
        if(null===$teammates){
            return (isset($this->teammates)) ?
                $this->teammates : null;
        }
        $this->teammates = $teammates;
        return $this;
    }

    public function settings (
        ?Settings $settings = null
        )
    {
        if(null===$settings){
            return (isset($this->settings)) ?
                $this->settings : null;
        }
        $this->settings = $settings;
        return $this;
    }

}

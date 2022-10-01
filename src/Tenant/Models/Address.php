<?php

namespace Tenant\Models;

use Tools\UniqueId;
use Tools\TimeStamp;

class Address
{
    private string $id;
    private string $type;
    private string $street;
    private string $house;
    private string $building;
    private string $floor;
    private string $room;
    private string $zone;
    private string $barangay;
    private string $town;
    private string $city;
    private string $province;
    private string $region;
    private string $state;
    private string $country;
    private string $zipcode;
    private TimeStamp $createdAt;
    private TimeStamp $updatedAt;


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


    public function street (
        ?string $street = null
        )
    {
        if(null===$street){
            return (isset($this->street)) ?
                $this->street : null;
        }
        $this->street = $street;
        return $this;
    }


    public function house (
        ?string $house = null
        )
    {
        if(null===$house){
            return (isset($this->house)) ?
                $this->house : null;
        }
        $this->house = $house;
        return $this;
    }

    public function building (
        ?string $building = null
        )
    {
        if(null===$building){
            return (isset($this->building)) ?
                $this->building : null;
        }
        $this->building = $building;
        return $this;
    }

    public function floor (
        ?string $floor = null
        )
    {
        if(null===$floor){
            return (isset($this->floor)) ?
                $this->floor : null;
        }
        $this->floor = $floor;
        return $this;
    }


    public function room (
        ?string $room = null
        )
    {
        if(null===$room){
            return (isset($this->room)) ?
                $this->room : null;
        }
        $this->room = $room;
        return $this;
    }


    public function zone (
        ?string $zone = null
        )
    {
        if(null===$zone){
            return (isset($this->zone)) ?
                $this->zone : null;
        }
        $this->zone = $zone;
        return $this;
    }


    public function barangay (
        ?string $barangay = null
        )
    {
        if(null===$barangay){
            return (isset($this->barangay)) ?
                $this->barangay : null;
        }
        $this->barangay = $barangay;
        return $this;
    }


    public function town (
        ?string $town = null
        )
    {
        if(null===$town){
            return (isset($this->town)) ?
                $this->town : null;
        }
        $this->town = $town;
        return $this;
    }


    public function city (
        ?string $city = null
        )
    {
        if(null===$city){
            return (isset($this->city)) ?
                $this->city : null;
        }
        $this->city = $city;
        return $this;
    }


    public function province (
        ?string $province = null
        )
    {
        if(null===$province){
            return (isset($this->province)) ?
                $this->province : null;
        }
        $this->province = $province;
        return $this;
    }


    public function region (
        ?string $region = null
        )
    {
        if(null===$region){
            return (isset($this->region)) ?
                $this->region : null;
        }
        $this->region = $region;
        return $this;
    }


    public function state (
        ?string $state = null
        )
    {
        if(null===$state){
            return (isset($this->state)) ?
                $this->state : null;
        }
        $this->state = $state;
        return $this;
    }


    public function country (
        ?string $country = null
        )
    {
        if(null===$country){
            return (isset($this->country)) ?
                $this->country : null;
        }
        $this->country = $country;
        return $this;
    }

    public function zipcode (
        ?string $zipcode = null
        )
    {
        if(null===$zipcode){
            return (isset($this->zipcode)) ?
                $this->zipcode : null;
        }
        $this->zipcode = $zipcode;
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

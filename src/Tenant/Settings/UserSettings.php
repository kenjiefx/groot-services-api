<?php

declare(strict_types=1);
namespace Tenant\Settings;

class UserSettings {
    
    private int $maxNumberOfUsersAllowed;
    private int $maxNumberOfStaffsAllowed;


    public function __construct(

        )
    {
        # constructor
    }


    public function maxNumberOfUsersAllowed (
        ?int $maxNumberOfUsersAllowed = null
        )
    {
        if(null===$maxNumberOfUsersAllowed){
            return (isset($this->maxNumberOfUsersAllowed)) ?
                $this->maxNumberOfUsersAllowed : null;
        }
        $this->maxNumberOfUsersAllowed = $maxNumberOfUsersAllowed;
        return $this;
    }


    public function maxNumberOfStaffsAllowed (
        ?int $maxNumberOfStaffsAllowed = null
        )
    {
        if(null===$maxNumberOfStaffsAllowed){
            return (isset($this->maxNumberOfStaffsAllowed)) ?
                $this->maxNumberOfStaffsAllowed : null;
        }
        $this->maxNumberOfStaffsAllowed = $maxNumberOfStaffsAllowed;
        return $this;
    }
}

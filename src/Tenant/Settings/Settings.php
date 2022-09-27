<?php

declare(strict_types=1);
namespace Tenant\Settings;

class Settings {

    private UserSettings $user;
    private AccountSettings $account;


    public function __construct(

        )
    {
        # constructor
    }


    public function user (
        ?UserSettings $user = null
        )
    {
        if(null===$user){
            return (isset($this->user)) ?
                $this->user : null;
        }
        $this->user = $user;
        return $this;
    }


    public function account (
        ?AccountSettings $account = null
        )
    {
        if(null===$account){
            return (isset($this->account)) ?
                $this->account : null;
        }
        $this->account = $account;
        return $this;
    }
    
}

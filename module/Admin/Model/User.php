<?php

namespace Admin\Model;


class User
{
    public $id;
    public $username;
    public $password;
    public $firstname;
    public $lastname;
    public $state;
    public $country;
    public $createdat;
    public $lastloginat;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        $this->password  = (!empty($data['password'])) ? $data['password'] : null;
        $this->firstname = (!empty($data['firstname'])) ? $data['firstname'] : null;
        $this->lastname  = (!empty($data['lastname'])) ? $data['lastname'] : null;
        $this->state = (!empty($data['state'])) ? $data['state'] : null;
        $this->country  = (!empty($data['country'])) ? $data['country'] : null;
        $this->createdat = (!empty($data['createdat'])) ? $data['createdat'] : null;
        $this->lastloginat  = (!empty($data['lastloginat'])) ? $data['lastloginat'] : null;
    }
}
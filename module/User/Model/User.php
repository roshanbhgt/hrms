<?php

namespace User\Model;


class User
{
    public $id;
    public $email;
    public $password;
    public $firstname;
    public $lastname;
    public $state;
    public $country;
    public $createdat;
    public $lastloginat;
    public $type;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->password  = (!empty($data['password'])) ? $data['password'] : null;
        $this->firstname = (!empty($data['firstname'])) ? $data['firstname'] : null;
        $this->lastname  = (!empty($data['lastname'])) ? $data['lastname'] : null;
        $this->state = (!empty($data['state'])) ? $data['state'] : null;
        $this->country  = (!empty($data['country'])) ? $data['country'] : null;
        $this->createdat = (!empty($data['createdat'])) ? $data['createdat'] : null;
        $this->lastloginat  = (!empty($data['lastloginat'])) ? $data['lastloginat'] : null;
        $this->type  = (!empty($data['type'])) ? $data['type'] : null;
    }
}
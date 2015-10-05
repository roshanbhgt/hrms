<?php

namespace Associate\Model;


class Employer
{
    public $id;
    public $assoc_id;
    public $firstname;
    public $lastname;
    public $company;
    public $email;
    public $createdat;
    public $updatedat;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->assoc_id     = (!empty($data['assoc_id'])) ? $data['assoc_id'] : null;
        $this->firstname = (!empty($data['firstname'])) ? $data['firstname'] : null;
        $this->lastname  = (!empty($data['lastname'])) ? $data['lastname'] : null;
        $this->company  = (!empty($data['company'])) ? $data['company'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->createdat = (!empty($data['createdat'])) ? $data['createdat'] : null;
        $this->updatedat  = (!empty($data['lastloginat'])) ? $data['lastloginat'] : null;
    }
}
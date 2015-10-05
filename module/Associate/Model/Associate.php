<?php

namespace Associate\Model;

class Associate
{
    public $id;
    public $username;
    public $password;
    public $type;
    public $createdat;
    public $updatedat;
    public $lastlogin;
        
    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->username     = (!empty($data['username'])) ? $data['username'] : null;
        $this->password     = (!empty($data['password'])) ? $data['password'] : null;
        $this->type     = (!empty($data['type'])) ? $data['type'] : null;
        $this->createdat     = (!empty($data['createdat'])) ? $data['createdat'] : null;
        $this->updatedat     = (!empty($data['updatedat'])) ? $data['updatedat'] : null;
        $this->lastlogin     = (!empty($data['lastlogin'])) ? $data['lastlogin'] : null;
    }
}
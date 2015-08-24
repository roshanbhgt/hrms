<?php
namespace Contact\Model;

class Contact
{
    public $id;
    public $name;
    public $email;
    public $message;
    public $createdat;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->message  = (!empty($data['message'])) ? $data['message'] : null;
        $this->createdat  = (!empty($data['createdat'])) ? $data['createdat'] : null;
    }
}
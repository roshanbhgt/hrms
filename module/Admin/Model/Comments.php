<?php
namespace Admin\Model;

class Comments
{
    public $id;
    public $postid;
    public $name;
    public $email;
    public $title;
    public $message;
    public $createdat;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->postid     = (!empty($data['postid'])) ? $data['postid'] : null;
        $this->name  = (!empty($data['name'])) ? $data['name'] : null;
        $this->email  = (!empty($data['email'])) ? $data['email'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->message  = (!empty($data['message'])) ? $data['message'] : null;        
        $this->createdat  = (!empty($data['createdat'])) ? $data['createdat'] : null;        
    }
}
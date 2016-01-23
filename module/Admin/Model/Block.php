<?php
namespace Admin\Model;

class Block
{
    public $id;
    public $title;
    public $identifier;
    public $content;
    public $createdat;
    public $updatedat;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->identifier = (!empty($data['identifier'])) ? $data['identifier'] : null;
        $this->content  = (!empty($data['content'])) ? $data['content'] : null;
        $this->createdat  = (!empty($data['createdat'])) ? $data['createdat'] : null;
        $this->updatedat  = (!empty($data['updatedat'])) ? $data['updatedat'] : null;
    }
}
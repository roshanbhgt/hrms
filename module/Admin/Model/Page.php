<?php
namespace Admin\Model;

class Page
{
    public $id;
    public $title;
    public $identifier;
    public $contents;
    public $createdat;
    public $updatedat;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->identifier = (!empty($data['identifier'])) ? $data['identifier'] : null;
        $this->contents  = (!empty($data['contents'])) ? $data['contents'] : null;
        $this->createdat  = (!empty($data['createdat'])) ? $data['createdat'] : null;
        $this->updatedat  = (!empty($data['updatedat'])) ? $data['updatedat'] : null;
    }
}
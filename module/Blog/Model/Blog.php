<?php
namespace Blog\Model;

class Blog
{
    public $id;
    public $title;
    public $description;
    public $author;
    public $createdat;
    public $updatedat;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->description  = (!empty($data['description'])) ? $data['description'] : null;
        $this->author  = (!empty($data['author'])) ? $data['author'] : null;
        $this->createdat  = (!empty($data['createdat'])) ? $data['createdat'] : null;
        $this->updatedat  = (!empty($data['updatedat'])) ? $data['updatedat'] : null;
    }
}
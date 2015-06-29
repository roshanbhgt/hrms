<?php
namespace Application\Model;

class Menu
{
    public $id;
    public $title;
    public $label;
    public $route;
    public $createdat;
    public $updatedat;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->label = (!empty($data['label'])) ? $data['label'] : null;
        $this->route  = (!empty($data['route'])) ? $data['route'] : null;
        $this->createdat  = (!empty($data['createdat'])) ? $data['createdat'] : null;
        $this->updatedat  = (!empty($data['updatedat'])) ? $data['updatedat'] : null;
    }
}
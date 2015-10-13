<?php
namespace Application\Model;

class Menu
{
    public $id;
    public $title;
    public $label;
    public $route;
    public $parent_id;
    public $sort_order;
    public $status;
    public $createdat;
    public $updatedat;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->label = (!empty($data['label'])) ? $data['label'] : null;
        $this->route  = (!empty($data['route'])) ? $data['route'] : null;
        $this->parent_id = (!empty($data['parent_id'])) ? $data['parent_id'] : null;
        $this->sort_order = (!empty($data['sort_order'])) ? $data['sort_order'] : null;
        $this->status  = (!empty($data['status'])) ? $data['status'] : null;
        $this->createdat  = (!empty($data['createdat'])) ? $data['createdat'] : null;
        $this->updatedat  = (!empty($data['updatedat'])) ? $data['updatedat'] : null;
    }
}
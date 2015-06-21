<?php
namespace Admin\Model;

class Banner
{
    public $id;
    public $title;
    public $banner;
    public $urlpath;
    public $status;
    public $sortorder;
    public $createdat;
    public $updatedat;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->banner = (!empty($data['banner'])) ? $data['banner'] : null;
        $this->urlpath  = (!empty($data['urlpath'])) ? $data['urlpath'] : null;
        $this->status  = (!empty($data['status'])) ? $data['status'] : null;
        $this->sortorder  = (!empty($data['sortorder'])) ? $data['sortorder'] : null;
        $this->createdat  = (!empty($data['createdat'])) ? $data['createdat'] : null;
        $this->updatedat  = (!empty($data['updatedat'])) ? $data['updatedat'] : null;
    }
}
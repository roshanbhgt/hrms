<?php

namespace User\Model;


class Resume
{
    public $id;
    public $userid;
    public $title;
    public $resume;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->userid     = (!empty($data['userid'])) ? $data['userid'] : null;
        $this->title     = (!empty($data['title'])) ? $data['title'] : null;
        $this->resume     = (!empty($data['resume'])) ? $data['resume'] : null;
        
    }
}
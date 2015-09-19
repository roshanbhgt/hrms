<?php

namespace User\Model;


class Resume
{
    public $id;
    public $userid;
    public $resume;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->userid     = (!empty($data['userid'])) ? $data['userid'] : null;
        $this->resume     = (!empty($data['resume'])) ? $data['resume'] : null;
        
    }
}
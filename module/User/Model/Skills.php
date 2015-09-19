<?php

namespace User\Model;


class Skills
{
    public $id;
    public $userid;
    public $title;
    public $exp_in_month;
    public $exp_in_year;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->userid     = (!empty($data['userid'])) ? $data['userid'] : null;
        $this->title     = (!empty($data['title'])) ? $data['title'] : null;
        $this->exp_in_month     = (!empty($data['exp_in_month'])) ? $data['exp_in_month'] : null;
        $this->exp_in_year     = (!empty($data['exp_in_year'])) ? $data['exp_in_year'] : null;
        
    }
}
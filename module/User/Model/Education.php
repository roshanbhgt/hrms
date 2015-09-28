<?php

namespace User\Model;


class Education
{
    public $id;
    public $userid;
    public $education;
    public $exp_in_month;
    public $exp_in_year;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->userid     = (!empty($data['userid'])) ? $data['userid'] : null;
        $this->education     = (!empty($data['education'])) ? $data['education'] : null;
        $this->duration_in_year     = (!empty($data['duration_in_year'])) ? $data['duration_in_year'] : null;
        $this->year_of_passing     = (!empty($data['year_of_passing'])) ? $data['year_of_passing'] : null;
        
    }
}
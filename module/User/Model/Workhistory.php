<?php

namespace User\Model;


class Workhistory
{
    public $id;
    public $userid;
    public $title;
    public $jobrole;
    public $jobdescription;
    public $start_date;
    public $end_date;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->userid     = (!empty($data['userid'])) ? $data['userid'] : null;
        $this->title     = (!empty($data['title'])) ? $data['title'] : null;
        $this->jobrole     = (!empty($data['jobrole'])) ? $data['jobrole'] : null;
        $this->jobdescription     = (!empty($data['jobdescription'])) ? $data['jobdescription'] : null;
        $this->start_date     = (!empty($data['start_date'])) ? $data['start_date'] : null;
        $this->end_date     = (!empty($data['end_date'])) ? $data['end_date'] : null;
        
    }
}
<?php

namespace User\Model;


class Jobapplication
{
    public $id;
    public $userid;
    public $jobid;
    public $jobtitle;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->userid     = (!empty($data['userid'])) ? $data['userid'] : null;
        $this->jobid     = (!empty($data['jobid'])) ? $data['jobid'] : null;
        $this->jobtitle     = (!empty($data['jobtitle'])) ? $data['jobtitle'] : null;
        
    }
}
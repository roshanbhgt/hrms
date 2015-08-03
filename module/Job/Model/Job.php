<?php

namespace Job\Model;

class Job
{
    public $id;
    public $userid;
    public $companyid;
    public $title;
    public $type;
    public $description;
    public $roles;
    public $skills;
    public $vacancy;
    public $salary;
    public $location;
    public $status;
    public $postedat;
    public $lastdate;
        
    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->userid     = (!empty($data['userid'])) ? $data['userid'] : null;
        $this->companyid     = (!empty($data['companyid'])) ? $data['companyid'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->type  = (!empty($data['type'])) ? $data['type'] : null;
        $this->description = (!empty($data['description'])) ? $data['description'] : null;
        $this->roles  = (!empty($data['roles'])) ? $data['roles'] : null;
        $this->skills  = (!empty($data['skills'])) ? $data['skills'] : null;
        $this->vacancy  = (!empty($data['vacancy'])) ? $data['vacancy'] : null;
        $this->salary = (!empty($data['salary'])) ? $data['salary'] : null;
        $this->location  = (!empty($data['location'])) ? $data['location'] : null;
        $this->status = (!empty($data['status'])) ? $data['status'] : null;
        $this->postedat = (!empty($data['postedat'])) ? $data['postedat'] : null;
        $this->lastdate  = (!empty($data['lastdate'])) ? $data['lastdate'] : null;
    }
}
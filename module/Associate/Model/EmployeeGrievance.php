<?php

namespace Associate\Model;


class EmployeeGrievance
{
    public $id;
    public $employee_id;
    public $name;
    public $location;
    public $client;
    public $email;
    public $contact;
    public $complaint;
    public $message;   
    public $createdat;
   
    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->employee_id = (!empty($data['employee_id'])) ? $data['employee_id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->location = (!empty($data['location'])) ? $data['location'] : null;
        $this->client  = (!empty($data['client'])) ? $data['client'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->contact = (!empty($data['contact'])) ? $data['contact'] : null;
        $this->complaint = (!empty($data['complaint'])) ? $data['complaint'] : null;
        $this->message = (!empty($data['message'])) ? $data['message'] : null;
        $this->createdat = (!empty($data['createdat'])) ? $data['createdat'] : null;
    }
}
<?php

namespace Associate\Model;

class Employee
{
    public $id;
    public $employer_id;
    public $employee_id;
    public $firstname;
    public $lastname;
    public $gender;
    public $email;
    public $doj;
    public $dob;
    public $dot;
    public $createdat;
    public $level;
    public $designation;
    public $updatedat;
    public $status;
    public $grosspay;
    public $leaves;
    public $address1;
    public $address2;
    public $state;
    public $country;
    public $city;
    public $postcode;
    public $phone;
    public $fax;
    public $picture; 
    public $bank_name;
    public $bank_account;
    public $pf_no; 
        
    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->employer_id     = (!empty($data['employer_id'])) ? $data['employer_id'] : null;
        $this->employee_id     = (!empty($data['employee_id'])) ? $data['employee_id'] : null;
        $this->firstname = (!empty($data['firstname'])) ? $data['firstname'] : null;
        $this->lastname  = (!empty($data['lastname'])) ? $data['lastname'] : null;
        $this->email  = (!empty($data['email'])) ? $data['email'] : null;
        $this->level  = (!empty($data['level'])) ? $data['level'] : null;
        $this->designation  = (!empty($data['designation'])) ? $data['designation'] : null;
        $this->gender = (!empty($data['gender'])) ? $data['gender'] : null;
        $this->address1  = (!empty($data['address1'])) ? $data['address1'] : null;
        $this->address2  = (!empty($data['address2'])) ? $data['address2'] : null;
        $this->state = (!empty($data['state'])) ? $data['state'] : null;
        $this->country  = (!empty($data['country'])) ? $data['country'] : null;
        $this->city = (!empty($data['city'])) ? $data['city'] : null;
        $this->postcode = (!empty($data['postcode'])) ? $data['postcode'] : null;
        $this->phone  = (!empty($data['phone'])) ? $data['phone'] : null;
        $this->fax  = (!empty($data['fax'])) ? $data['fax'] : null;
        $this->picture  = (!empty($data['picture'])) ? $data['picture'] : null;
        $this->createdat  = (!empty($data['createdat'])) ? $data['createdat'] : null;
        $this->updatedat  = (!empty($data['updatedat'])) ? $data['updatedat'] : null;
        $this->doj  = (!empty($data['doj'])) ? $data['doj'] : null;
        $this->dot  = (!empty($data['dot'])) ? $data['dot'] : null;
        $this->dob  = (!empty($data['dob'])) ? $data['dob'] : null;
        $this->status  = (!empty($data['status'])) ? $data['status'] : null;
        $this->grosspay  = (!empty($data['grosspay'])) ? $data['grosspay'] : null;
        $this->leaves  = (!empty($data['leaves'])) ? $data['leaves'] : null;
        $this->bank_name  = (!empty($data['bank_name'])) ? $data['bank_name'] : null;
        $this->bank_account  = (!empty($data['bank_account'])) ? $data['bank_account'] : null;
        $this->pf_no  = (!empty($data['pf_no'])) ? $data['pf_no'] : null;
    }
}
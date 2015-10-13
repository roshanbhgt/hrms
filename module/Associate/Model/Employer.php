<?php

namespace Associate\Model;


class Employer
{
    public $id;
    public $assoc_id;
    public $employer_id;
    public $firstname;
    public $lastname;
    public $company;
    public $email;
    public $gender;
    public $level;
    public $designation;
    public $dob;
    public $doj;
    public $dot;
    public $address1;
    public $address2;
    public $city;
    public $state;
    public $country;
    public $postcode;
    public $phone;
    public $fax;
    public $logo;
    public $createdat;
    public $updatedat;
    public $status;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->assoc_id     = (!empty($data['assoc_id'])) ? $data['assoc_id'] : null;
        $this->company     = (!empty($data['company'])) ? $data['company'] : null;
        $this->firstname = (!empty($data['firstname'])) ? $data['firstname'] : null;
        $this->lastname  = (!empty($data['lastname'])) ? $data['lastname'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->gender = (!empty($data['gender'])) ? $data['gender'] : null;
        $this->level = (!empty($data['level'])) ? $data['level'] : null;
        $this->designation = (!empty($data['designation'])) ? $data['designation'] : null;
        $this->dob = (!empty($data['dob'])) ? $data['dob'] : null;
        $this->doj = (!empty($data['doj'])) ? $data['doj'] : null;
        $this->dot = (!empty($data['dot'])) ? $data['dot'] : null;
        $this->address1 = (!empty($data['address1'])) ? $data['address1'] : null;
        $this->address2 = (!empty($data['address2'])) ? $data['address2'] : null;
        $this->city = (!empty($data['city'])) ? $data['city'] : null;
        $this->state = (!empty($data['state'])) ? $data['state'] : null;
        $this->country = (!empty($data['country'])) ? $data['country'] : null;
        $this->postcode = (!empty($data['postcode'])) ? $data['postcode'] : null;
        $this->phone = (!empty($data['phone'])) ? $data['phone'] : null;
        $this->fax = (!empty($data['fax'])) ? $data['fax'] : null;
        $this->logo = (!empty($data['logo'])) ? $data['logo'] : null;
        $this->createdat = (!empty($data['createdat'])) ? $data['createdat'] : null;
        $this->updatedat  = (!empty($data['updatedat'])) ? $data['updatedat'] : null;
        $this->status  = (!empty($data['status'])) ? $data['status'] : null;
    }
}
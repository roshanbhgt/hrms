<?php

namespace User\Model;

class Jobseeker
{
    public $id;
    public $userid;
    public $firstname;
    public $middlename;
    public $lastname;
    public $gender;
    public $dob;
    public $address1;
    public $address2;
    public $address3;
    public $state;
    public $country;
    public $city;
    public $postcode;
    public $phone;
    public $mobile;
    public $fax;
    public $description;
    public $url;
    public $picture;
        
    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->userid     = (!empty($data['userid'])) ? $data['userid'] : null;
        $this->firstname = (!empty($data['firstname'])) ? $data['firstname'] : null;
        $this->middlename  = (!empty($data['middlename'])) ? $data['middlename'] : null;
        $this->lastname = (!empty($data['lastname'])) ? $data['lastname'] : null;
        $this->gender = (!empty($data['gender'])) ? $data['gender'] : null;
        $this->dob = (!empty($data['dob'])) ? $data['dob'] : null;
        $this->address1  = (!empty($data['address1'])) ? $data['address1'] : null;
        $this->address2  = (!empty($data['address2'])) ? $data['address2'] : null;
        $this->address3  = (!empty($data['address3'])) ? $data['address3'] : null;
        $this->state = (!empty($data['state'])) ? $data['state'] : null;
        $this->country  = (!empty($data['country'])) ? $data['country'] : null;
        $this->city = (!empty($data['city'])) ? $data['city'] : null;
        $this->postcode = (!empty($data['postcode'])) ? $data['postcode'] : null;
        $this->phone  = (!empty($data['phone'])) ? $data['phone'] : null;
        $this->mobile  = (!empty($data['mobile'])) ? $data['mobile'] : null;
        $this->fax  = (!empty($data['fax'])) ? $data['fax'] : null;
        $this->description  = (!empty($data['description'])) ? $data['description'] : null;
        $this->url  = (!empty($data['url'])) ? $data['url'] : null;
        $this->picture  = (!empty($data['picture'])) ? $data['picture'] : null;
    }
}
<?php

namespace Associate\Model;

class Employee
{
    public $id;
    public $userid;
    public $companyname;
    public $companytype;
    public $industrytype;
    public $address1;
    public $address2;
    public $address3;
    public $state;
    public $country;
    public $city;
    public $postcode;
    public $contactnumber;
    public $fax;
    public $description;
    public $url;
    public $logo;
        
    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->userid     = (!empty($data['userid'])) ? $data['userid'] : null;
        $this->companyname = (!empty($data['companyname'])) ? $data['companyname'] : null;
        $this->companytype  = (!empty($data['companytype'])) ? $data['companytype'] : null;
        $this->industrytype = (!empty($data['industrytype'])) ? $data['industrytype'] : null;
        $this->address1  = (!empty($data['address1'])) ? $data['address1'] : null;
        $this->address2  = (!empty($data['address2'])) ? $data['address2'] : null;
        $this->address3  = (!empty($data['address3'])) ? $data['address3'] : null;
        $this->state = (!empty($data['state'])) ? $data['state'] : null;
        $this->country  = (!empty($data['country'])) ? $data['country'] : null;
        $this->city = (!empty($data['city'])) ? $data['city'] : null;
        $this->postcode = (!empty($data['postcode'])) ? $data['postcode'] : null;
        $this->contactnumber  = (!empty($data['contactnumber'])) ? $data['contactnumber'] : null;
        $this->fax  = (!empty($data['fax'])) ? $data['fax'] : null;
        $this->description  = (!empty($data['description'])) ? $data['description'] : null;
        $this->url  = (!empty($data['url'])) ? $data['url'] : null;
        $this->logo  = (!empty($data['logo'])) ? $data['logo'] : null;
    }
}
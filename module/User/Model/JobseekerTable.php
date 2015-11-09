<?php

namespace User\Model;
use Zend\Db\TableGateway\TableGateway;

class JobseekerTable
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        $resultSet->buffer();
        return $resultSet; 
    }

    public function getJobseeker($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('userid' => $id));
        
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }
    
    public function getJobseekerDetails($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }

    public function saveJobseeker($jobseeker)
    {
        $data = array(
            'userid'     => $jobseeker->userid,
            'firstname' => $jobseeker->firstname,
            'lastname'  => $jobseeker->lastname,
            'middlename' => $jobseeker->middlename,
            'gender' => $jobseeker->gender,
            'dob' => $jobseeker->dob,
            'address1'  => $jobseeker->address1,
            'address2'  => $jobseeker->address2,
            'address3'  => $jobseeker->address3,
            'state' => $jobseeker->state,
            'country' => $jobseeker->country,
            'city' => $jobseeker->city,
            'postcode' => $jobseeker->postcode,
            'phone' => $jobseeker->phone,
            'mobile' => $jobseeker->mobile,
            'fax' => $jobseeker->fax,
            'description' => $jobseeker->description,
            'url' => $jobseeker->url
        );
        
        $id = (int)$jobseeker->userid;
        if (!$this->getJobseeker($id)) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getJobseeker($id))
            {
                $this->tableGateway->update($data, array('userid' => $id));
            } else {
                throw new \Exception('User with id does not exist');
            }
        }
    }

    public function deleteJobseeker($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    public function updatePicture($data)
    {
        $id = (int)$data['id'];
        if ($id != 0 && $data['picture'] != '') {
            if ($this->getJobseeker($id)) {
                return $this->tableGateway->update($data, array('userid' => $id));
            } else {
                $data['userid'] = $data['id'];
                unset($data['id']);
                $this->tableGateway->insert($data);
            }
        }
    }
    
    public function updateJobseeker($company)
    {        
        $data = array(
            'userid'     => $company->userid,
            'companyname' => $company->companyname,
            'companytype'  => $company->companytype,
            'industrytype' => $company->industrytype,
            'address1'  => $company->address1,
            'address2'  => $company->address2,
            'address3'  => $company->address3,
            'state' => $company->state,
            'country' => $company->country,
            'city' => $company->city,
            'postcode' => $company->postcode,
            'contactnumber' => $company->contactnumber,
            'fax' => $company->fax,
            'description' => $company->description,
            'url' => $company->url,
        );
        
        $userid = (int)$company->userid;
        $id = (int)$company->id;
        if (!$this->getCompany($userid)) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCompany($userid))
            {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('User with id does not exist');
            }
        }
    }
    
    public function updateDesc($data)
    {
        $id = (int)$data['id'];
        if ($id != 0 && $data['description'] != '') {
            if ($this->getCompanyDetails($id)) {
                return $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Company with id does not exist');
            }
        }
    }
}
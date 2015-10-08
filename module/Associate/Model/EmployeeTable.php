<?php

namespace Associate\Model;
use Zend\Db\TableGateway\TableGateway;

class EmployeeTable
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function fetchEmployeeAll($id)
    {
        $resultSet = $this->tableGateway->select(array('employer_id' => $id));
        return $resultSet;
    }
    
    public function getEmployee($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('employee_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }
    
    public function getEmployeeDetails($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }

    public function saveEmployee($employee)
    {
        $data = array(
            'employer_id'     => $employee->employer_id,
            'employee_id'     => $employee->employee_id,
            'firstname' => $employee->firstname,
            'lastname'  => $employee->lastname,
            'gender' => $employee->gender,
            'email' => $employee->email,
            'level' => $employee->level,
            'designation' => $employee->designation,
            'dob' => $employee->dob,
            'doj' => $employee->doj,
            /* 'address1'  => $company->address1,
            'address2'  => $company->address2,
            'state' => $company->state,
            'country' => $company->country,
            'city' => $company->city,
            'postcode' => $company->postcode,
            'phone' => $company->contactnumber,
            'fax' => $company->fax,
            'logo' => $company->logo,*/
            'status' => $employee->status,
            'createdat' => date('Y-m-d h:i:s'),
        );
        
        $id = (int)$employee->employer_id;
        if (!$this->getEmployee($id)) {
            $this->tableGateway->insert($data);
        } else {
            throw new \Exception('User with id does not exist');
        }
    }

    public function deleteCompany($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    public function updateLogo($data)
    {
        $id = (int)$data['id'];
        if ($id != 0 && $data['logo'] != '') {
            if ($this->getCompanyDetails($id)) {
                return $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Company with id does not exist');
            }
        }
    }
    
    public function updateCompany($company)
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
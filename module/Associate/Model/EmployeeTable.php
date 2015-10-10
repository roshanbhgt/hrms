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
            throw new \Exception('Employee with id does not exist');
        }
    }

    
    public function updateEmployee($employee)
    {
        $data = array(
            'firstname' => $employee->firstname,
            'lastname'  => $employee->lastname,
            'gender' => $employee->gender,
            'dob' => $employee->dob,
            'address1'  => $employee->address1,
            'address2'  => $employee->address2,
            'state' => $employee->state,
            'country' => $employee->country,
            'city' => $employee->city,
            'postcode' => $employee->postcode,
            'phone' => $employee->phone,
            'fax' => $employee->fax,
            'status' => $employee->status,
            'updatedat' => date('Y-m-d h:i:s'),
        );
        
        echo $id = (int)$employee->employee_id;
        if ($this->getEmployee($id)) {
            $this->tableGateway->update($data, array('employee_id' => $id));
        } else {
            throw new \Exception('Employee with id does not exist');
        }
    }
    
    public function deleteEmployee($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    public function updateLogo($associate)
    {
        $id = (int)$associate['id'];
        if ($this->getEmployee($id))
        {
            $associate['updatedat'] = date('Y-m-d h:m:s');
            $this->tableGateway->update($associate, array('employee_id' => $id));
        } else {
            throw new \Exception('Associate employee with id does not exist');
        }
        
        return ;
    }
}
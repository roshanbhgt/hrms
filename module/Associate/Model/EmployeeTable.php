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

    public function getCompany($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('userid' => $id));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }
    
    public function getCompanyDetails($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }

    public function saveCompany($company)
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
            'logo' => $company->logo,
        );
        
        $id = (int)$company->userid;
        if (!$this->getCompany($id)) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCompany($id))
            {
                $this->tableGateway->update($data, array('userid' => $id));
            } else {
                throw new \Exception('User with id does not exist');
            }
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
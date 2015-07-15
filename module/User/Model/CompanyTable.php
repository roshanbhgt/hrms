<?php

namespace User\Model;
use Zend\Db\TableGateway\TableGateway;

class CompanyTable
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
}
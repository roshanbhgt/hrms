<?php

namespace Associate\Model;
use Zend\Db\TableGateway\TableGateway;
use Application\Model\Password;

class EmployerTable
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

    public function getEmployer($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function getEmployerDetail($id){
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('assoc_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveEmployer($employer)
    {
        $data = array(
            'assoc_id' => $employer->assoc_id,
            'firstname' => $employer->firstname,
            'lastname'  => $employer->lastname,
            'email' => $employer->email,
            'company' => $employer->company
        );

        $id = (int)$employer->id;
        if ($id == 0) {
            $data['createdat'] = date('Y-m-d h:m:s');
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($id)) {
                $data['lastloginat'] = date('Y-m-d h:m:s');
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('User with id does not exist');
            }
        }
        return $id;
    }

    public function updateEmployer($employer)
    {
        
        $id = (int)$employer->assoc_id;
        
        $data = array(
            'assoc_id' => $employer->assoc_id,
            'firstname' => $employer->firstname,
            'lastname'  => $employer->lastname,
            'email' => $employer->email,
            'company' => $employer->company,
            'address1' => $employer->address1,
            'address2' => $employer->address2,
            'city' => $employer->city,
            'state' => $employer->state,
            'country' => $employer->country,
            'phone' => $employer->phone,
            'fax' => $employer->fax,
            'postcode' => $employer->postcode,
        );
        if ($this->getEmployerDetail($id)) {
            return $this->tableGateway->update($data, array('assoc_id' => $id));
        } else {
            throw new \Exception('User with id does not exist');
        }
        
    }

    public function deleteEmployer($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    public function isDuplcateEmail($email, $type){
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('email' => $id));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }
    
    public function updateLogo($associate)
    {
        $id = (int)$associate['id'];
        if ($this->getEmployerDetail($id))
        {
            $associate['updatedat'] = date('Y-m-d h:m:s');
            $this->tableGateway->update($associate, array('assoc_id' => $id));
        } else {
            throw new \Exception('Associate with id does not exist');
        }
        
        return ;
    }
}
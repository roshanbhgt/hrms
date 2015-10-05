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
        $id = (int)$employer->id;
        $password = new Password();
        
        if ($id != 0 && $user->password == '') {
            $data = array(
                'assoc_id' => $employer->assoc_id,
                'firstname' => $employer->firstname,
                'lastname'  => $employer->lastname,
                'email' => $employer->email,
                'company' => $employer->company
            );
            if ($this->getEmployer($id)) {
                return $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('User with id does not exist');
            }
        } elseif ($id != 0 && $employer->password != '') {
            $data = array(
                'password' => $password->create($employer->password),
            );
            if ($this->getEmployer($id)) {
                return $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('User with id does not exist');
            }
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
    
    public function updatePassword($data)
    {
        $id = (int)$data->id;
        $password = new Password();
        
        if ($id != 0 && $data->password != '') {
            $data = array(
                'firstname' => $data->firstname,
                'lastname'  => $data->lastname,
                'email' => $data->email,
                'password' => $password->create($data->password),
            );
            
            if ($this->getUser($id)) {
                return $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('User with id does not exist');
            }
        }
    }
}
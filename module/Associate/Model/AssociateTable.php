<?php

namespace Associate\Model;
use Zend\Db\TableGateway\TableGateway;
use Application\Model\Password;

class AssociateTable
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

    public function getAssociate($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }
    
    public function getAssociateDetails($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }

    public function saveAssociate($associate)
    {
        $password = new Password();
        $data = array(
            'username'     => $associate->username,
            'password' => $password->create($associate->password),
            'type'  => $associate->type,
        );
        
        $id = (int)$associate->id;
        if (!$this->getAssociate($id)) {
            $data['createdat'] = date('Y-m-d h:m:s');
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
        } else {
            if ($this->getAssociate($id))
            {
                $data['updatedat'] = date('Y-m-d h:m:s');
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Associate with id does not exist');
            }
        }
        
        $this->saveUsername($id);
        
        return $id;
    }
    
    public function updatePass($associate)
    {
        if($associate->password != ''){
            $password = new Password();
            $data = array(
                'id' => (int)$associate->id,
                'password' => $password->create($associate->password),
            );
            $id = (int)$associate->id;
            if ($this->getAssociate($id))
            {
                $data['updatedat'] = date('Y-m-d h:m:s');
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Associate with id does not exist');
            }
        }
        return ;
    }

    public function deleteAssociate($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    public function saveUsername($id){
        $username = '';
        if(strlen($id) == 1){
            $username = '0000'.$id;
        }elseif(strlen($id) == 2){
            $username = '000'.$id;
        }elseif(strlen($id) == 3){
            $username = '00'.$id;
        }elseif(strlen($id) == 4){
            $username = '0'.$id;
        }else{
            $username = $id;    
        }
        
        $data['username'] = $username;
        $this->tableGateway->update($data, array('id' => $id));
    }
    
    
}
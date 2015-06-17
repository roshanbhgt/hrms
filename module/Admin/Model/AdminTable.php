<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;

class AdminTable
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

    public function getAdmin($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveAdmin($admin)
    {   
        $data = array(
            'firstname' => $admin->firstname,
            'lastname'  => $admin->lastname,
            'username' => $admin->username,
            'password'  => $admin->password,
            'state' => $admin->state,
            'country'  => $admin->country,
        );
        
        $id = (int)$admin->id;
        if ($id == 0) {
            $data['createdat'] = date('Y-m-d h:m:s');
            return $this->tableGateway->insert($data);
        } else {
            if ($this->getAdmin($id)) {
                $data['lastloginat'] = date('Y-m-d h:m:s');
                return $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function updateAdmin($admin)
    {
        $id = (int)$admin->id;
        if ($id != 0 && $admin->password == '') {
            $data = array(
                'firstname' => $admin->firstname,
                'lastname'  => $admin->lastname,
                'username' => $admin->username,
                'state' => $admin->state,
                'country'  => $admin->country,
            );
            if ($this->getAdmin($id)) {
                return $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('User with id does not exist');
            }
        } elseif ($id != 0 && $admin->password != '') {
            $data = array(
                'password' => $admin->password,
            );
            if ($this->getAdmin($id)) {
                return $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('User with id does not exist');
            }
        }
    }

    public function deleteAdmin($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}

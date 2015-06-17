<?php

namespace User\Model;
use Zend\Db\TableGateway\TableGateway;

class UserTable
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

    public function getUser($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveUser($user)
    {
        $data = array(
            'firstname' => $user->firstname,
            'lastname'  => $user->lastname,
            'email' => $user->email,
            'password'  => $user->password,
        );

        $id = (int)$user->id;
        if ($id == 0) {
            $data['createdat'] = date('Y-m-d h:m:s');
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($id))
            {
                $data['lastloginat'] = date('Y-m-d h:m:s');
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('User with id does not exist');
            }
        }
    }

    public function updateUser($user)
    {
        $id = (int)$user->id;
        if ($id != 0 && $user->password == '') {
            $data = array(
                'firstname' => $user->firstname,
                'lastname'  => $user->lastname,
                'email' => $user->email,
                'state' => $user->state,
                'country'  => $user->country,
            );
            if ($this->getUser($id)) {
                return $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('User with id does not exist');
            }
        } elseif ($id != 0 && $user->password != '') {
            $data = array(
                'password' => $user->password,
            );
            if ($this->getUser($id)) {
                return $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('User with id does not exist');
            }
        }
    }

    public function deleteUser($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}
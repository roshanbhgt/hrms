<?php

namespace Associate\Model;
use Zend\Db\TableGateway\TableGateway;

class EmployeeGrievanceTable
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

    public function getEmployeeGriv($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('employee_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function save($employeegriv)
    {
        $data = array(
            'employee_id' => $employeegriv->employee_id,
            'name' => $employeegriv->name,
            'location'  => $employeegriv->location,
            'email' => $employeegriv->email,
            'client' => $employeegriv->client,
            'contact' => $employeegriv->contact,
            'complaint' => $employeegriv->complaint,
            'message'  => $employeegriv->message,
            'createdat' => date('Y-m-d h:m:s')
        );

        $id = (int)$employeegriv->id;
        if ($id == 0) {
            $data['createdat'] = date('Y-m-d h:m:s');
            $this->tableGateway->insert($data);
        } else {
            throw new \Exception('User with id does not exist');
        }
        return $id;
    }

    public function delete($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}
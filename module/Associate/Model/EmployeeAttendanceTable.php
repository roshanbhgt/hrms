<?php

namespace Associate\Model;
use Zend\Db\TableGateway\TableGateway;

class EmployeeAttendanceTable
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

    public function fetchAttendanceAll($id)
    {
        $resultSet = $this->tableGateway->select(array('employer_id' => $id));
        return $resultSet;
    }
    
    public function getEmployeeAttendance($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('employee_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }

    public function saveAttendance($employee)
    {
        $data = array(
            'employee_id'     => $employee->employee_id,
            'start_time' => $employee->start_time,
            'end_time'  => $employee->end_time,
            'total' => $employee->total
        );
        
        $id = (int)$employee->employer_id;
        if (!$this->getEmployee($id)) {
            $this->tableGateway->insert($data);
        } else {
            throw new \Exception('User with id does not exist');
        }
    }

    public function deleteAttendance($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
}
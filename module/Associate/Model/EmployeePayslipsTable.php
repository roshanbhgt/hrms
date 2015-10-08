<?php

namespace Associate\Model;
use Zend\Db\TableGateway\TableGateway;

class EmployeePayslipsTable
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
    
    public function getEmployeePayslips($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('employee_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }

    public function savePayslips($payslips)
    {
        $data = array(
            'employee_id' => $payslips['employee_id'],
            'basic' => $payslips['basic'],
            'hra'  => $payslips['hra'],
            'conveyance' => $payslips['conveyance'],
            'medical_allowance' => $payslips['medical_allowance'],
            'children_selfdeduction' => $payslips['children_selfdeduction'],
            'lta' => $payslips['lta'],
            'pf' => $payslips['pf'],
            'income_tax' => $payslips['income_tax'],
            'prof_tax' => $payslips['prof_tax'],
            'lop' => $payslips['lop'],
            'createdat' => date('Y-m-d h:i:s'),
        );
        
//        $id = (int)$employee->employer_id;
//        if (!$this->getEmployee($id)) {
            $this->tableGateway->insert($data);
//        } else {
//            throw new \Exception('User with id does not exist');
//        }
    }

    public function deletePayslips($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
}
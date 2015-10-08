<?php

namespace Associate\Model;

class EmployeeAttendance
{
    public $id;
    public $employee_id;
    public $start_time;
    public $end_time;
    public $total;
    
        
    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->employee_id     = (!empty($data['employee_id'])) ? $data['employee_id'] : null;
        $this->start_time = (!empty($data['start_time'])) ? $data['start_time'] : null;
        $this->end_time  = (!empty($data['end_time'])) ? $data['end_time'] : null;
        $this->total  = (!empty($data['total'])) ? $data['total'] : null;
    }
}
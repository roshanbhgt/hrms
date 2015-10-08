<?php

namespace Associate\Model;

class EmployeePayslips
{
    public $id;
    public $employee_id;
    public $basic;
    public $hra;
    public $conveyance;
    public $medical_allowance;
    public $lta;
    public $pf;
    public $income_tax;
    public $prof_tax;
    public $lop;
    public $createdat;
    
        
    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->employee_id     = (!empty($data['employee_id'])) ? $data['employee_id'] : null;
        $this->basic = (!empty($data['basic'])) ? $data['basic'] : null;
        $this->hra  = (!empty($data['hra'])) ? $data['hra'] : null;
        $this->conveyance  = (!empty($data['conveyance'])) ? $data['conveyance'] : null;
        $this->medical_allowance = (!empty($data['medical_allowance'])) ? $data['medical_allowance'] : null;
        $this->children_selfdeduction  = (!empty($data['children_selfdeduction'])) ? $data['children_selfdeduction'] : null;
        $this->lta  = (!empty($data['lta'])) ? $data['lta'] : null;
        $this->pf = (!empty($data['pf'])) ? $data['pf'] : null;
        $this->income_tax  = (!empty($data['income_tax'])) ? $data['income_tax'] : null;
        $this->prof_tax = (!empty($data['prof_tax'])) ? $data['prof_tax'] : null;
        $this->lop = (!empty($data['lop'])) ? $data['lop'] : null;
        $this->createdat  = (!empty($data['createdat'])) ? $data['createdat'] : null;
    }
}
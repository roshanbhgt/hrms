<?php

namespace User\Model;
use Zend\Db\TableGateway\TableGateway;

class SkillsTable
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

    public function getSkills($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('userid' => $id));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }

    public function saveSkills($skill)
    {       
        $data = array(
            'userid' => $skill['userid'],
            'title' => strtolower($skill['title']),
            'exp_in_month' => $skill['exp_in_month'],
            'exp_in_year' => $skill['exp_in_year'],
        );
        $title = $skill['title'];
        $id = $skill['userid'];
        if ($this->getCheckDuplicate($title)) {
            $this->tableGateway->update($data, array('userid' => $id));
        } else {
            $this->tableGateway->insert($data);
        }        
        return $id;
    }
    
    public function deleteSkill($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    public function getCheckDuplicate($title){
        $rowset = $this->tableGateway->select(array('title' => strtolower($title)));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }
    
}
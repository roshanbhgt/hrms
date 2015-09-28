<?php

namespace User\Model;
use Zend\Db\TableGateway\TableGateway;

class EducationTable
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

    public function getEducation($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('userid' => $id));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }

    public function saveEducation($education)
    {       
        $data = array(
            'userid' => $education['userid'],
            'education' => strtolower($education['education']),
            'duration_in_year' => $education['duration_in_year'],
            'year_of_passing' => $education['year_of_passing'],
        );
        $title = $education['education'];
        $id = $education['userid'];
        if ($this->getCheckDuplicate($title)) {
            $this->tableGateway->update($data, array('userid' => $id));
        } else {
            $this->tableGateway->insert($data);
        }        
        return $id;
    }
    
    public function deleteEducation($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    public function getCheckDuplicate($title){
        $rowset = $this->tableGateway->select(array('education' => strtolower($title)));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }
    
}
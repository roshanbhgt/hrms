<?php

namespace User\Model;
use Zend\Db\TableGateway\TableGateway;

class WorkhistoryTable
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

    public function getWorkhistory($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('userid' => $id));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }

    public function saveWorkhistory($workhistory)
    {       
        $data = array(
            'userid' => $workhistory['userid'],
            'title' => strtolower($workhistory['title']),
            'jobrole' => strtolower($workhistory['jobrole']),
            'jobdescription' => strtolower($workhistory['jobdescription']),
            'start_date' => $workhistory['start_date'],
            'end_date' => $workhistory['end_date'],
        );
        $title = $workhistory['title'];
        $id = $workhistory['userid'];
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
        $rowset = $this->tableGateway->select(array('title' => strtolower($title)));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }
    
}
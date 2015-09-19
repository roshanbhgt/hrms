<?php

namespace User\Model;
use Zend\Db\TableGateway\TableGateway;

class ResumeTable
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

    public function getResume($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('userid' => $id));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }

    public function saveResume($resume)
    {       
        $data = array(
            'userid' => $resume['id'],
            'resume' => $resume['resume'],
        );
       
        $id = (int) $resume['id'];
        if ($this->getResume($id)) {
            $this->tableGateway->update($data, array('userid' => $id));
        } else {
            $this->tableGateway->insert($data);
        }
        
        return $id;
    }
    
    public function deleteResume($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
}
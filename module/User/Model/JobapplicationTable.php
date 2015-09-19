<?php

namespace User\Model;
use Zend\Db\TableGateway\TableGateway;

class JobapplicationTable
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

    public function getJobapplication($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('jobid' => $id));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }

    public function saveApplication($application)
    {       
        $data = array(
            'userid' => $application['userid'],
            'jobid' => $application['jobid'],
            'jobtitle' => $application['jobtitle'],
            'applydate' => date('Y-m-d H:mm:ss'),
        );
       
        $id = (int) $resume['id'];
        if ($this->getResume($id)) {
            $this->tableGateway->update($data, array('userid' => $id));
        } else {
            $this->tableGateway->insert($data);
        }
        
        return $id;
    }
    
    public function deleteApplication($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
}
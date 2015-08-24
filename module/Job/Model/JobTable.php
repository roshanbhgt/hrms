<?php

namespace Job\Model;
use Zend\Db\TableGateway\TableGateway;

class JobTable
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

    public function getJob($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return FALSE;
        }
        return $row;
    }
    
    public function getEmployerJob($id)
    {
        $id  = (int) $id;
        $resultSet = $this->tableGateway->select(array('userid' => $id));
        
        return $resultSet;
    }

    public function saveJob($job)
    {
        $data = array(
            'userid'     => $job->userid,
            'title' => $job->title,
            'type'  => $job->type,
            'position'  => $job->position,
            'description' => $job->description,
            'roles'  => $job->roles,
            'skills'  => $job->skills,
            'vacancy'  => $job->vacancy,
            'salary' => $job->salary,
            'location' => $job->location,
            'status' => $job->status,
        );
        
        $userid = (int)$job->userid;
        $id = (int)$job->id;
        
        if (!$this->getJob($id)) {
            $data['postedat'] = date('y-m-d h:i:s');
            $this->tableGateway->insert($data);
        } else {
            if ($this->getJob($id))
            {
                $data['updatedat'] = date('y-m-d h:i:s');
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('User with id does not exist');
            }
        }
    }

    public function deleteJob($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
}
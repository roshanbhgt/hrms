<?php

namespace Job\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class JobApplicationTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    } 
    
    public function fetchAll($paginated=false)
    {
        if ($paginated) {
             // create a new Select object for the table album
             $select = new Select('user_jobseeker_jobapplication');
             // create a new result set based on the Album entity
             $resultSetPrototype = new ResultSet();
             $resultSetPrototype->setArrayObjectPrototype(new Job());
             // create a new pagination adapter object
             $paginatorAdapter = new DbSelect(
                 // our configured select object
                 $select,
                 // the adapter to run it against
                 $this->tableGateway->getAdapter(),
                 // the result set to hydrate
                 $resultSetPrototype
             );
             $paginator = new Paginator($paginatorAdapter);
             return $paginator;
         }
         $resultSet = $this->tableGateway->select();
         return $resultSet;
    }
    
    public function fetchAllByUser($id){
        $resultSet = $this->tableGateway->select(array('userid' => $id));
        return $resultSet;
    }
     
    
    public function saveJobApplication($job)
    {
        $data = array(
            'userid'     => $job->userid,
            'jobid'     => $job->jobid,
            'jobtitle' => $job->jobtitle,
            'applydate' => date('y-m-d h:i:s')
        );
        
        $id = (int)$job->id;
        
        if (!$id) {
            $this->tableGateway->insert($data);
        } else {
            throw new \Exception('Application with id does not exist');
        }
    }

    public function deleteJobApplication($id)
    {
        return $this->tableGateway->delete(array('id' => $id));
        
    }
    
}
<?php

namespace Job\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class JobTable
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
             $select = new Select('user_company_jobpost');
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
    
    
    public function recentJob($id)
    {
        if ($this->getJob($id))
        {
            $data['recentpost'] = 'active';
            $data['updatedat'] = date('y-m-d h:i:s');
            $this->tableGateway->update($data, array('id' => $id));
        } else {
            throw new \Exception('User with id does not exist');
        }
    }
    
    public function fetchAllRecent()
    {
        $resultSet = $this->tableGateway->select(array('recentpost' => 'active', 'status' => 'active'));
        
        return $resultSet;
    }
    
    public function getSearchResult($q, $loc=null, $paginated=true)
    {   
        if ($paginated) {
            $sql = $this->tableGateway->getSql();
            $select = $sql->select();
            $select->where
                ->NEST->
                    like('title', "%$q%")
                ->OR->
                    like('description', "%$q%")
                ->OR->
                    like('skills', "%$q%")
                ->OR->
                    like('location', "%$loc%")
                ->UNNEST;
            $select->limit(100);
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Job());
            $paginatorAdapter = new DbSelect(
                 $select,
                 $this->tableGateway->getAdapter(),
                 $resultSetPrototype
             );
            $paginator = new Paginator($paginatorAdapter);
            // echo $sql->getSqlstringForSqlObject($select);
            // die;
            return $paginator;
         }
         return ;
    }
}
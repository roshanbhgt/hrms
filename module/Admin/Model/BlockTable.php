<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;

class BlockTable
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

    public function getBlock($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function getBlockByIdentifier($identifier)
    {
        $rowset = $this->tableGateway->select(array('identifier' => $identifier));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $url");
        }
        return $row;
    }

    public function saveBlock($block)
    {
        $data = array(
            'identifier' => preg_replace('/\s+/', '-', strtolower($block->identifier)),
            'title' => $block->title,
            'content'  => $block->content
        );

        $id = (int)$block->id;
        if ($id == 0) {
            $data['createdat'] = date("Y-m-d h:i:s");
            $this->tableGateway->insert($data);
        } else {
            if ($this->getBlock($id)) {
                $data['updatedat'] = date("Y-m-d h:i:s");
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteBlock($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}

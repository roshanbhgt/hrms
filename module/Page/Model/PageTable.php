<?php
namespace Page\Model;

use Zend\Db\TableGateway\TableGateway;

class PageTable
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

    public function getPage($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function getPageByIdentifier($url)
    {
        $rowset = $this->tableGateway->select(array('identifier' => $url));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $url");
        }
        return $row;
    }

    public function savePage(Page $page)
    {
        $data = array(
            'title' => $page->title,
            'contents'  => $page->contents,
            'createdat'  => $page->createdat,
            'updatedat'  => $page->updatedat,
        );

        $id = (int)$page->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getPage($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deletePage($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}

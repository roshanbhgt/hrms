<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Application\Model\Url;

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

    public function savePage($page)
    {
        $url = new Url();
        $data = array(
            'title' => $page->title,
            'contents'  => $page->contents,
        );

        $id = (int)$page->id;
        if ($id == 0) {
            $identifier = $url->formatUrlKey($page->title);
            $data['identifier'] = $identifier;
            $data['createdat'] = date('y-m-d h:i:s');
            $this->tableGateway->insert($data);
        } else {
            if ($this->getPage($id)) {
                $data['updatedat']  = date('y-m-d h:i:s');
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

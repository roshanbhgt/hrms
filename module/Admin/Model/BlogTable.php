<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Application\Model\Url;

class BlogTable
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

    public function getBlog($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveBlog($blog)
    {
        $url = new Url();
        $data = array(
            'title' => $blog->title,
            'description'  => $blog->description,
            'author'  => $blog->author,
        );

        $id = (int)$blog->id;
        if ($id == 0) {
            $identifier = 'blog/'.$url->formatUrlKey($blog->title);
            $data['identifier'] = $identifier;
            $data['createdat'] = date('y-m-d h:i:s');
            $this->tableGateway->insert($data);
        } else {
            if ($this->getBlog($id)) {
                $data['updatedat'] = date('y-m-d h:i:s');
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteBlog($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}

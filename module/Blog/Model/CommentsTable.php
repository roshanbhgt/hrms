<?php
namespace Blog\Model;

use Zend\Db\TableGateway\TableGateway;

class CommentsTable
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

    public function getComments($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('postid' => $id));
        /* $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        } */
        return $rowset;
    }

    public function saveComments($comments)
    {
        $data = array(
            'postid' => $comments->postid,
            'name'  => $comments->name,
            'email'  => $comments->email,
            'title' => $comments->title,
            'message'  => $comments->message,
        );

        $id = (int)$comments->id;
        if ($id == 0) {
            $data['createdat'] = date('y-m-d h:i:s');
            $this->tableGateway->insert($data);
        } else {
            if ($this->getComments($id)) {
                $data['createdat'] = date('y-m-d h:i:s');
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

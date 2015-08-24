<?php
namespace Contact\Model;

use Zend\Db\TableGateway\TableGateway;

class ContactTable
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

    public function getContact($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveContact($contact)
    {
        $data = array(
            'name' => $contact->name,
            'email'  => $contact->email,
            'message'  => $contact->message,
        );

        $id = (int)$contact->id;
        if ($id == 0) {
            $data['createdat'] = date('y-m-d h:i:s');
            $this->tableGateway->insert($data);
        }
    }

    public function deleteContact($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}

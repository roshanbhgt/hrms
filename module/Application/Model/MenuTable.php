<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class MenuTable
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

    public function getMenu($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveMenu(Menu $menu)
    {
        $data = array(
            'title' => $menu->title,
            'label'  => $menu->label,
            'route'  => $menu->route,
        );

        $id = (int)$menu->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getMenu($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteMenu($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}

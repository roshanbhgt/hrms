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
    
    public function fetchAllSubmenu($id)
    {
        $resultSet = $this->tableGateway->select(array('parent_id' => $id));
//        $resultSet = $this->tableGateway->select(
//            function (\Zend\Db\Sql\Select $select) {
//                $select->where(array('parent_id' => $ident));
//                $select->order('sort_order ASC')->limit(10);
//            }
//        );

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

    public function saveMenu($menu)
    {
        $data = array(
            'title' => $menu->title,
            'label'  => $menu->label,
            'route'  => $menu->route,
            'parent_id' => $menu->parent_id,
            'sort_order' => $menu->sort_order,
            'status' => $menu->status,
        );

        $id = (int)$menu->id;
        if ($id == 0) {
			$data['createdat'] = date('Y-m-d h:m:s');
            $this->tableGateway->insert($data);
        } else {
            if ($this->getMenu($id)) {
				$data['updatedat'] = date('Y-m-d h:m:s');
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

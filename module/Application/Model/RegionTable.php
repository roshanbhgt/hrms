<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class RegionTable
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

    public function getRegion($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveRegion($region)
    {
        $data = array(
            'country_id' => $region->country_id,
            'code'  => $region->code,
            'default_name'  => $region->default_name,
        );

        $id = (int)$region->id;
        if ($id == 0) {
			$data['createdat'] = date('Y-m-d h:m:s');
            $this->tableGateway->insert($data);
        } else {
            if ($this->getRegion($id)) {
				$data['updatedat'] = date('Y-m-d h:m:s');
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteRegion($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}

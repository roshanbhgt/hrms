<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class CountryTable
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

    public function getCountry($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveCountry($country)
    {
        $data = array(
            'iso' => $country->iso,
            'name'  => $country->name,
            'nicename'  => $country->nicename,
			'iso3'  => $country->iso3,
        );

        $id = (int)$country->id;
        if ($id == 0) {
			$data['createdat'] = date('Y-m-d h:m:s');
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCountry($id)) {
				$data['updatedat'] = date('Y-m-d h:m:s');
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteCountry($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}

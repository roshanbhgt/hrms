<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;

class BannerTable
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

    public function getBanner($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveBanner($banner)
    {
        $data = array(
            'title' => $banner['title'],
            'banner'  => $banner['banner'],
            'urlpath'  => $banner['urlpath'],
            'sortorder'  => $banner['sortorder'],
            'status'  => $banner['status'],
        );
        
        $id = (int)$banner['id'];
        if ($id == 0) {
            $data['createdat'] = date('y-m-d h:i:s');
            $this->tableGateway->insert($data);
        } else {
            if ($this->getBanner($id)) {
                $data['updatedat'] = date('y-m-d h:i:s');
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteBanner($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}

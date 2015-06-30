<?php
namespace Application\Model;

class Country
{
    public $id;
    public $iso;
    public $name;
    public $nicename;
	public $iso3;
    public $createdat;
    public $updatedat;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->iso = (!empty($data['iso'])) ? $data['iso'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->nicename  = (!empty($data['nicename'])) ? $data['nicename'] : null;
		$this->iso3  = (!empty($data['iso3'])) ? $data['iso3'] : null;
        $this->createdat  = (!empty($data['createdat'])) ? $data['createdat'] : null;
        $this->updatedat  = (!empty($data['updatedat'])) ? $data['updatedat'] : null;
    }
}
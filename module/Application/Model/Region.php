<?php
namespace Application\Model;

class Region
{
    public $id;
    public $country_id;
    public $code;
    public $default_name;
    public $createdat;
    public $updatedat;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->country_id = (!empty($data['country_id'])) ? $data['country_id'] : null;
        $this->code = (!empty($data['code'])) ? $data['code'] : null;
        $this->default_name  = (!empty($data['default_name'])) ? $data['default_name'] : null;
        $this->createdat  = (!empty($data['createdat'])) ? $data['createdat'] : null;
        $this->updatedat  = (!empty($data['updatedat'])) ? $data['updatedat'] : null;
    }
}
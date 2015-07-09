<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;

class AjaxController extends AbstractActionController
{
    
    protected $regionTable;
    
    public function getRegionTable()
    {
        if (!$this->regionTable) {
            $sm = $this->getServiceLocator();
            $this->regionTable = $sm->get('Application\Model\RegionTable');
        }
        return $this->regionTable;
    }
    
    public function regionAction()
    {
        $country = $this->getRequest()->getPost('country');
        $region = array();
        $result = $this->getRegionTable()->getRegionByCountry($country);
        
        if($result = $this->getRegionTable()->getRegionByCountry($country)){            
            $i = 0;
            foreach($result as $val){
                $region[$i]['code']= $val->code;
                $region[$i]['title']= $val->default_name;
                $i++;
            }
        }
        
        if (count($region)>0) {
            $data['status'] = 'success';
            $data['region'] = $region;
        } else {
            $data['status'] = 'error';
        }
        
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine( 'Content-Type', 'application/json' );
        $response->setContent(json_encode($data));
        return $response;
    }
}

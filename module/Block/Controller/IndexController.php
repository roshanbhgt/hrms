<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Block\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $blockTable;
    
    public function getBlockTable()
    {
        if (!$this->blockTable) {
            $sm = $this->getServiceLocator();
            $this->blockTable = $sm->get('Block\Model\BlockTable');
        }
        return $this->blockTable;
    }

    public function indexAction()
    {
        $url = $this->params()->fromRoute('url', 0);
                
        return new ViewModel(array(
            'page' => $this->blockTable()->getPageByIdentifier($url),
        ));
    }
}

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

class IndexController extends AbstractActionController
{ 
    
    protected $bannerTable;
	protected $jobTable;
	protected $companyTable;
    
    public function getBannerTable()
    {
        if (!$this->bannerTable) {
            $sm = $this->getServiceLocator();
            $this->bannerTable = $sm->get('Admin\Model\BannerTable');
        }
        return $this->bannerTable;
    }
	
	public function getJobTable()
    {
        if (!$this->jobTable) {
            $sm = $this->getServiceLocator();
            $this->jobTable = $sm->get('Job\Model\JobTable');
        }
        return $this->jobTable;
    }
	
	public function getCompanyTable()
    {
        if (!$this->companyTable) {
            $sm = $this->getServiceLocator();
            $this->companyTable = $sm->get('User\Model\CompanyTable');
        }
        return $this->companyTable;
    }
    
    public function indexAction()
    {
        $banners = array();
		$recentjobs = array();
		$recentemployers = array();
        
        $view = new ViewModel(
                    array(
                        'banners' => $this->getBannerTable()->fetchAll(),
                        'bannersmenu' => $this->getBannerTable()->fetchAll(),
						'recentjobs' => $this->getJobTable()->fetchAllRecent(),
						'recentemployers' => $this->getCompanyTable()->fetchAllRecent(),
                    )
                );
        return $view;
    }
}

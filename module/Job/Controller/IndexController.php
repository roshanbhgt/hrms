<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Job\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $jobTable;
    protected $jobapplicationTable;

    public function getJobTable()
    {
        if (!$this->jobTable) {
            $sm = $this->getServiceLocator();
            $this->jobTable = $sm->get('Job\Model\JobTable');
        }
        return $this->jobTable;
    }
    
    public function getJobApplicationTable()
    {
        if (!$this->jobapplicationTable) {
            $sm = $this->getServiceLocator();
            $this->jobapplicationTable = $sm->get('Job\Model\JobApplicationTable');
        }
        return $this->jobapplicationTable;
    }

    public function indexAction()
    { 
        // grab the paginator from the AlbumTable
        $paginator = $this->getJobTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage(10);

        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }
    
    public function viewAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        return new ViewModel(array(
            'job' => $this->getJobTable()->getJob($id),
        ));
    }
    
    public function applyAction()
    {
        $request = $this->getRequest();

        if($request->getPost()){
            if($this->getJobApplicationTable()->saveJobApplication($request->getPost())){
                $this->flashmessenger()->addMessage('You have successfully applied for this job.');
            }
        }
        
        return $this->redirect()->toRoute('job');
    }
    
    public function searchAction(){
        
        $request = $this->getRequest();        
        
        if($request->getPost()){
            $data = $request->getPost();
            // grab the paginator from the JobTable
            $paginator = $this->getJobTable()->getSearchResult($data['keyword']);
            // set the current page to what has been passed in query string, or to 1 if none set
            $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
            // set the number of items per page to 10
            $paginator->setItemCountPerPage(10);
            if(count($paginator)){
                return new ViewModel(array(
                    'paginator' => $paginator
                ));
            }else{
                return new ViewModel(array(
                    'paginator' => $paginator,
                ));
            }
        }        
    }
    
}

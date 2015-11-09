<?php
/**
 * Created by PhpStorm.
 * User: roshan.bhagat
 * Date: 12/15/2014
 * Time: 6:50 PM
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class JobsController extends AbstractActionController {
    protected $storage;
    protected $authservice;
    protected $jobTable;
	protected $jobseekerTable;
	protected $jobapplicationTable;

    public function getAuthService()
    {
        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()
                ->get('AuthService');
        }

        return $this->authservice;
    }

    public function getSessionStorage()
    {
        if (!$this->storage) {
            $this->storage = $this->getServiceLocator()
                ->get('Admin\Model\AdminAuthStorage');
        }
        return $this->storage;
    }

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
	
	public function getJobseekerTable()
    {
        if (!$this->jobseekerTable) {
            $sm = $this->getServiceLocator();
            $this->jobseekerTable = $sm->get('User\Model\JobseekerTable');
        }
        return $this->jobseekerTable;
    }

    public function indexAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        return new ViewModel(array(
            'jobs' => $this->getJobTable()->fetchAll(),
        ));
    }
    
    public function deleteAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);

        if($this->getJobTable()->deleteJob($id)){
            $this->flashmessenger()->addSuccessMessage('Query has been deleted successfully.');
        }

        return $this->redirect()->toRoute('admin-jobs');
    }
    
    public function addinrecentjobAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);

        if($this->getJobTable()->recentJob($id)){
            $this->flashmessenger()->addSuccessMessage('Jabs has been added in recent post successfully.');
        }

        return $this->redirect()->toRoute('admin-jobs');
    }
	
	public function viewAction(){
		if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }
		
		$id = (int) $this->params()->fromRoute('id', 0);
        return new ViewModel(array(
            'job' => $this->getJobTable()->getJob($id),
        ));
		
	}
	
	public function jobapplicationAction(){
            if (! $this->getServiceLocator()
                ->get('AuthService')->hasIdentity()){
                return $this->redirect()->toRoute('admin-login');
            }

            $id = (int) $this->params()->fromRoute('id', 0);

            return new ViewModel(array(
                'id' => $id,
                'jobapplication' => $this->getJobApplicationTable()->fetchAllByJobId($id),
                'jobseekers' => $this->getJobseekerTable()->fetchAll()
            ));
	}
} 
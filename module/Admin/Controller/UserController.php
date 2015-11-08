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

class UserController extends AbstractActionController {
    protected $storage;
    protected $authservice;
    protected $userTable;
	protected $companyTable;

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

    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Admin\Model\UserTable');
        }
        return $this->userTable;
    }
	
	public function getCompanyTable()
    {
        if (!$this->companyTable) {
            $sm = $this->getServiceLocator();
            $this->companyTable = $sm->get('User\Model\CompanyTable');
        }
        return $this->companyTable;
    }

    public function employerAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }
        
        $users = $this->getUserTable()->getUserByType('employer');
        
        return new ViewModel(array(
            'users' => $users,
        ));
    }
    
    public function jobseekerAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $users = $this->getUserTable()->getUserByType('jobseeker');
        
        return new ViewModel(array(
            'users' => $users,
        ));
    }
    
    public function editAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        return new ViewModel(array(
            'user' => $this->getUserTable()->getUser($id),
        ));
    }
    
    public function deleteAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        
        if($this->getUserTable()->deleteUser($id)){
            $this->flashmessenger()->addMessage('User has been deleted successfully.');
        }
        
        return $this->redirect()->toRoute('admin-user');
    }
	
	public function addinrecentemployerAction(){
		if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
		$this->getCompanyTable()->recentEmployer($id);
		return $this->redirect()->toRoute('admin-user', array('action' => 'employer'));
	}
} 
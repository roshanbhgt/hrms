<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Associate\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Password;
use Zend\Session\Container;
use Zend\File\Transfer\Adapter\Http;

class EmployeeController extends AbstractActionController
{
    protected $storage;
    protected $authservice;
    protected $associateTable;
    protected $employerTable;

    public function getAuthService()
    {
        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()
                ->get('AssociateAuthService');
        }

        return $this->authservice;
    }
 
    public function getSessionStorage()
    {
        if (!$this->storage) {
            $this->storage = $this->getServiceLocator()
                ->get('Associate\Model\AssociateAuthStorage');
        }

        return $this->storage;
    }

    public function getSession()
    {
        return (object)$this->getAuthService()->getStorage()->read();
    }
    
    public function getAssociateTable()
    {
        if (!$this->associateTable) {
            $sm = $this->getServiceLocator();
            $this->associateTable = $sm->get('Associate\Model\AssociateTable');
        }
        return $this->associateTable;
    }
    
    public function getEmployeeTable()
    {
        if (!$this->jobseekerTable) {
            $sm = $this->getServiceLocator();
            $this->jobseekerTable = $sm->get('Associate\Model\EmployeeTable');
        }
        return $this->jobseekerTable;
    }

    public function indexAction()
    {
        if (!$this->getServiceLocator()
            ->get('UserAuthService')->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
        
        $id = $this->getSession()->id;
        $jobseeker = $this->getUserTable()->getUser($id);
        $jobseekerdet = $this->getJobseekerTable()->getJobseeker($id);
        
        // Store data in session
        $usersession = new Container('user');
        $usersession->id = $jobseeker->id;
        $usersession->username = $jobseeker->firstname .' '.$jobseeker->lastname;
        $usersession->location = $jobseeker->state .', '.$jobseeker->country;
        $usersession->type = $jobseeker->type;
        $usersession->picture = $jobseekerdet->picture;

        return new ViewModel(array(
            'user' => $jobseeker,
            'messages'  => $this->flashmessenger()->getMessages(),
        ));
    }
    
    public function editAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        return new ViewModel(array(
            'user' => $this->getUserTable()->getUser($id),
            'jobseeker' => $this->getJobseekerTable()->getJobseeker($id),
            'countrys' => $this->getCountryTable()->fetchAll(),
        ));
    }
    
    public function updateAction()
    {
        if (!$this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }

        $request = $this->getRequest();

        if ($request->isPost()){
            if($this->getJobseekerTable()->saveJobseeker($request->getPost())){
                $this->flashmessenger()->addMessage('Your account has been updated successfully.');
            }
        }

        return $this->redirect()->toRoute('jobseeker');

    }

    public function changepassAction()
    {
        if (!$this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('associate');
        }
        $id = (int) $this->params()->fromRoute('id', 0);
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            if($data['password'] == $data['confpassword']){ 
                if($this->getAssociateTable()->updatePass($data)){
                    $this->flashMessenger()->addSuccessMessage('Password updated successfully.');
                }
            }else{
                $this->flashMessenger()->addErrorMessage('Password and confirm password not matching.');
            }
            $id = $data['id'];
        }
        
        return new ViewModel(array(
            'id' => $id,
        ));
    }
    
}

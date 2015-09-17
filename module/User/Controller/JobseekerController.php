<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Password;
use Zend\Session\Container;

class JobseekerController extends AbstractActionController
{
    protected $storage;
    protected $authservice;
    protected $userTable;
    protected $usercompanyTable;
    protected $countryTable;

    public function getAuthService()
    {
        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()
                ->get('UserAuthService');
        }

        return $this->authservice;
    }
 
    public function getSessionStorage()
    {
        if (!$this->storage) {
            $this->storage = $this->getServiceLocator()
                ->get('User\Model\UserAuthStorage');
        }

        return $this->storage;
    }

    public function getSession()
    {
        return (object)$this->getAuthService()->getStorage()->read();
    }

    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('User\Model\UserTable');
        }
        return $this->userTable;
    }
    
    public function getCountryTable()
    {
        if (!$this->countryTable) {
            $sm = $this->getServiceLocator();
            $this->countryTable = $sm->get('Application\Model\CountryTable');
        }
        return $this->countryTable;
    }
    
    public function getUserCompanyTable()
    {
        if (!$this->usercompanyTable) {
            $sm = $this->getServiceLocator();
            $this->usercompanyTable = $sm->get('User\Model\UserCompanyTable');
        }
        return $this->usercompanyTable;
    }

    public function indexAction()
    {
        if (!$this->getServiceLocator()
            ->get('UserAuthService')->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
        
        $id = $this->getSession()->id;
        $jobseeker = $this->getUserTable()->getUser($id);
        
        // Store data in session
        $usersession = new Container('user');
        $usersession->id = $jobseeker->id;
        $usersession->username = $jobseeker->firstname .' '.$jobseeker->lastname;
        $usersession->location = $jobseeker->state .', '.$jobseeker->country;
        $usersession->type = $jobseeker->type;

        return new ViewModel(array(
            'user' => $jobseeker,
            'messages'  => $this->flashmessenger()->getMessages(),
        ));
    }

    public function changepassAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
        $id = (int) $this->params()->fromRoute('id', 0);
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            if($data['password'] == $data['confpassword']){ 
                if($this->getUserTable()->updatePassword($data)){
                    $this->flashMessenger()->addSuccessMessage('Password updated successfully.');
                }
            }else{
                $this->flashMessenger()->addErrorMessage('Password and confirm password not matching.');
            }
            $id = $data['id'];
        }
        
        return new ViewModel(array(
            'id' => $id,
            'user' => $this->getUserTable()->getUser($id),
        ));
    }
}

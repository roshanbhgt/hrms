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

class IndexController extends AbstractActionController
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
    
    public function getEmployerTable()
    {
        if (!$this->employerTable) {
            $sm = $this->getServiceLocator();
            $this->employerTable = $sm->get('Associate\Model\EmployerTable');
        }
        return $this->employerTable;
    }
    
    public function indexAction()
    {
        //if already login, redirect to success page
        if (!$this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('associate', array('action' => 'login'));
        }

        $view = new ViewModel();
        return $view;

    }
    
    public function loginAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('associate');
        }

        $view = new ViewModel(array('messages' => $this->flashmessenger()->getMessages()));
        return $view;

    }

    public function authenticateAction()
    {
        $request = $this->getRequest();
        $redirect = 'assciate';

        if ($request->isPost()){
            $pass = new Password();
            //check authentication...
            $this->getAuthService()->getAdapter()
                ->setIdentity($request->getPost('username'))
                ->setCredential($pass->verify($request->getPost('password')));

            $result = $this->getAuthService()->authenticate();

            foreach($result->getMessages() as $message)
            {
                //save message temporary into flashmessenger
                $this->flashmessenger()->addMessage("You have been login successfully.");
            }

            if ($result->isValid()) {
                //check if it has rememberMe :
                if ($request->getPost('rememberme') == 1 ) {
                    $this->getSessionStorage()
                        ->setRememberMe(1);
                    //set storage again
                    $this->getAuthService()->setStorage($this->getSessionStorage());
                }

                $row = $this->getAuthService()->getAdapter()->getResultRowObject();

                $this->getEmployerTable()->getEmployerDetail($row->id);
                
                $this->getAuthService()->getStorage()->write(
                    array(
                        'id'      => $row->id,
                        'username'   => $request->getPost('username'),
                        'type'   => $row->type,
                        'ip_address' => $this->getRequest()->getServer('REMOTE_ADDR'),
                        'user_agent'    => $request->getServer('HTTP_USER_AGENT')
                    )
                );
            }
        }
        
        if($row->type == 'employer'){
            return $this->redirect()->toRoute('employer', array('action' => 'index'));
        } elseif($row->type == 'employee') {
            return $this->redirect()->toRoute('employee', array('action' => 'index'));
        }
        return $this->redirect()->toRoute($redirect);
    }

    public function forgetpassAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('user');
        }

        $view = new ViewModel(array( 'messages'  => $this->flashmessenger()->getMessages()));
        return $view;
    }
	
    public function registerAction()
    {	 
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('associate');
        }

        $view = new ViewModel();
        
        return $view;
    }

    public function logoutAction()
    {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();

        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute('assciate', array('action' => 'index'));
    }

    public function createAction()
    {
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
        
        $request = $this->getRequest();
        
        if ($request->isPost()){
            $data = $request->getPost();
            if ($request->getPost('type') == 'employer') {
                if($userid = $this->getAssociateTable()->saveAssociate($data)){
                    $data['assoc_id'] = $userid;
                    if($this->getEmployerTable()->saveEmployer($data)){
                        $this->flashmessenger()->addMessage('Employer information saved successfully.');
                    }
                }
            } elseif($request->getPost('type') == 'employee') {
                if($this->getUserTable()->saveUser($request->getPost())){
                    $this->flashmessenger()->addMessage('You personal information saved successfully.');
                }
            }
        }
        
        return $this->redirect()->toRoute('associate');
    }

    public function updateAction()
    {
        if (!$this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }

        $request = $this->getRequest();

        if ($request->isPost()){
            if($this->getUserTable()->updateUser($request->getPost())){
                $this->flashmessenger()->addMessage('Your account has been updated successfully.');
            }
        }

        return $this->redirect()->toRoute('user');

    }

    public function editAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }

        return new ViewModel(array(
            'user' => $this->getUserTable()->fetchAll(),
        ));
    }
}

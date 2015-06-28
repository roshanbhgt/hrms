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

class IndexController extends AbstractActionController
{
    protected $storage;
    protected $authservice;
    protected $userTable;
    protected $usercompanyTable;

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

        return new ViewModel(array(
            'user' => $this->getUserTable()->getUser($this->getSession()->id),
            'messages'  => $this->flashmessenger()->getMessages(),
        ));
    }

    public function loginAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('user');
        }

        $view = new ViewModel(array('messages' => $this->flashmessenger()->getMessages()));
        return $view;

    }

    public function authenticateAction()
    {
        $request = $this->getRequest();
        $redirect = 'login';

        if ($request->isPost()){
            $pass = new Password();
            //check authentication...
            $this->getAuthService()->getAdapter()
                ->setIdentity($request->getPost('email'))
                ->setCredential($pass->verify($request->getPost('password')));

            $result = $this->getAuthService()->authenticate();

            foreach($result->getMessages() as $message)
            {
                //save message temporary into flashmessenger
                $this->flashmessenger()->addMessage("You have been login successfully.");
            }

            if ($result->isValid()) {
                $redirect = 'user';
                //check if it has rememberMe :
                if ($request->getPost('rememberme') == 1 ) {
                    $this->getSessionStorage()
                        ->setRememberMe(1);
                    //set storage again
                    $this->getAuthService()->setStorage($this->getSessionStorage());
                }

                $row = $this->getAuthService()->getAdapter()->getResultRowObject();

                $this->getUserTable()->saveUser($row);

                $this->getAuthService()->getStorage()->write(
                    array('id'          => $row->id,
                        'firstname'   => $row->firstname,
                        'email'   => $request->getPost('email'),
                        'type'   => $request->getPost('type'),
                        'ip_address' => $this->getRequest()->getServer('REMOTE_ADDR'),
                        'user_agent'    => $request->getServer('HTTP_USER_AGENT'))
                );
            }
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
		/* 
			//if already login, redirect to success page
			if ($this->getAuthService()->hasIdentity()){
				return $this->redirect()->toRoute('user');
			}
			
			$view = new ViewModel(array( 'messages'  => $this->flashmessenger()->getMessages()));
			return $view;
		*/
		return $this->redirect()->toRoute('login');
    }
	
	public function registerjobseekerAction()
    {
		/* 
			//if already login, redirect to success page
			if ($this->getAuthService()->hasIdentity()){
				return $this->redirect()->toRoute('user');
			}
			
			$view = new ViewModel(array( 'messages'  => $this->flashmessenger()->getMessages()));
			return $view;
		*/
		return $this->redirect()->toRoute('login');
    }
	
    public function registercompanyAction()
    {
		 
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()){
                return $this->redirect()->toRoute('user');
        }

        $view = new ViewModel(array( 'messages'  => $this->flashmessenger()->getMessages()));
        return $view;
		
    }

    public function logoutAction()
    {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();

        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute('login');
    }

    public function createAction()
    {
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }

        $request = $this->getRequest();
        
        if ($this->getUserTable()->isDuplcateEmail($request->getPost('email'),$request->getPost('type'))) {
            $this->flashmessenger()->addMessage('Account with email id already exist');
            if($request->getPost('type') == 'employer'){
                return $this->redirect()->toRoute('registercompany');
            }elseif($request->getPost('type') == 'jobseeker'){
                return $this->redirect()->toRoute('registerjobseeker');
            }
            
        }

        if ($request->isPost()){
            $userid = $this->getUserTable()->saveUser($request->getPost());
            if($userid){
                $this->flashmessenger()->addMessage('You account have been created successfully.');
            }
            $data = $request->getPost();
            $data['userid'] = $userid;
            if ($request->getPost('type') == 'employer') {
                if($this->getUserCompanyTable()->saveCompany($data)){
                    $this->flashmessenger()->addMessage('Your company information saved successfully.');
                }
            } elseif($request->getPost('type') == 'jobseeker') {
                if($this->getUserTable()->saveUser($request->getPost())){
                    $this->flashmessenger()->addMessage('You personal information saved successfully.');
                }
            }
            
        }
        
        return $this->redirect()->toRoute('login');
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

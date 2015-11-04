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
use Zend\File\Transfer\Adapter\Http;

class IndexController extends AbstractActionController
{
    protected $storage;
    protected $authservice;
    protected $userTable;
    protected $countryTable;
    protected $companyTable;
    protected $jobseekerTable;
    protected $resumeTable;

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
    
    public function getCompanyTable()
    {
        if (!$this->companyTable) {
            $sm = $this->getServiceLocator();
            $this->companyTable = $sm->get('User\Model\CompanyTable');
        }
        return $this->companyTable;
    }
    
    public function getJobseekerTable()
    {
        if (!$this->jobseekerTable) {
            $sm = $this->getServiceLocator();
            $this->jobseekerTable = $sm->get('User\Model\JobseekerTable');
        }
        return $this->jobseekerTable;
    }
    
    public function getResumeTable()
    {
        if (!$this->resumeTable) {
            $sm = $this->getServiceLocator();
            $this->resumeTable = $sm->get('User\Model\ResumeTable');
        }
        return $this->resumeTable;
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
                $this->flashmessenger()->addMessage($message);
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

                $this->getUserTable()->saveUser($row);
                
                $this->getAuthService()->getStorage()->write(
                    array(
                        'id'      => $row->id,
                        'email'   => $request->getPost('email'),
                        'type'   => $row->type,
                        'ip_address' => $this->getRequest()->getServer('REMOTE_ADDR'),
                        'user_agent'    => $request->getServer('HTTP_USER_AGENT')
                    )
                );
                
                if($row->type == 'jobseeker'){
                    $usersession->id = $row->id;
                    $usersession->name = $row->firstname.' '.$row->lastname;
                    $usersession->type = $row->type;
                }
            }
        }
        
        if($row->type == 'employer'){
            return $this->redirect()->toRoute('employer', array('action' => 'index'));
        } elseif($row->type == 'jobseeker') {
            return $this->redirect()->toRoute('jobseeker', array('action' => 'index'));
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
   
    public function registerjobseekerAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()){ 
            return $this->redirect()->toRoute('user');
        }

       $view = new ViewModel(
                    array( 
                        'messages'  => $this->flashmessenger()->getMessages(),
                        'countrys'  => $this->getCountryTable()->fetchAll()
                    )
                );
        return $view;
    }
	
    public function registercompanyAction()
    {
		 
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()){
                return $this->redirect()->toRoute('user');
        }

        $view = new ViewModel(
                    array( 
                        'messages'  => $this->flashmessenger()->getMessages(),
                        'countrys'  => $this->getCountryTable()->fetchAll()
                    )
                );
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
            $data = $request->getPost();
            if ($request->getPost('type') == 'employer') {
                if($userid = $this->getUserTable()->saveUser($data)){
                    $data['userid'] = $userid;
                    if($this->getCompanyTable()->saveCompany($data)){
                        $this->flashmessenger()->addMessage('Your company information saved successfully.');
                    }
                }
            } elseif($request->getPost('type') == 'jobseeker') {
                
                if($userid = $this->getUserTable()->saveUser($data)){
                    // Make certain to merge the files info!
                    $data = array_merge_recursive(
                        $request->getPost()->toArray(),
                        $request->getFiles()->toArray()
                    );
                    
                    $data['id'] = $userid;
                    $data['title'] = 'Curriculum Vitae';

                    // Define a transport and set the destination on the server
                    $upload = new Http();
                    $upload->setDestination($_SERVER['DOCUMENT_ROOT']."/public/media/cv");

                    try {
                        // This takes care of the moving and making sure the file is there
                        if($upload->receive()){
                            $data['resume'] = str_replace($_SERVER['DOCUMENT_ROOT']."/public", '',$upload->getFileName());
                            $data['resume'] = str_replace('\\', '/', $data['resume']);
                            echo '<pre>';
                print_r($data);
                exit;
                            if($data['resume'] != ''){ 
                                if($this->getResumeTable()->saveResume($data)){
                                    $this->flashMessenger()->addSuccessMessage('Picture updated successfully.');
                                }
                            }else{
                                $this->flashMessenger()->addErrorMessage('Please upload valid doc file.');
                            }
                        }
                    } catch (Zend_File_Transfer_Exception $e) {
                        echo $e->message();
                    }
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

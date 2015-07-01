<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\File\Transfer\Adapter\Http;
use Zend\Json;
use Application\Model\Password;


class IndexController extends AbstractActionController
{

    protected $storage;
    protected $authservice;
    protected $adminTable;

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
    
    public function getSession()
    {
        return (object)$this->getAuthService()->getStorage()->read();
    }

    public function getAdminTable()
    {
        if (!$this->adminTable) {
            $sm = $this->getServiceLocator();
            $this->adminTable = $sm->get('Admin\Model\AdminTable');
        }
        return $this->adminTable;
    }

    public function indexAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        return new ViewModel(array(
            'admin' => $this->getAdminTable()->getAdmin($this->getSession()->id),
            'messages'  => $this->flashmessenger()->getMessages(),
            'lists' => $this->getAdminTable()->fetchAll(),
        ));
    }

    public function loginAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('admin');
        }

        // retrieve ReCaptchaService instance and generate a new captcha
        $captcha = $this->getServiceLocator()->get('ReCaptchaService')->generate();

        $view = new ViewModel(array(
            'captcha'  => $captcha
        ));
        return $view;

    }

    public function authenticateAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()){
            $password = new Password();
            $encyPass = $password->verify($request->getPost('password'));
            //check authentication...
            $this->getAuthService()->getAdapter()
                ->setIdentity($request->getPost('username'))
                ->setCredential($encyPass);

            $result = $this->getAuthService()->authenticate();

            foreach($result->getMessages() as $message)
            {
                //save message temporary into flashmessenger
                $this->flashmessenger()->addSuccessMessage("You have been login successfully.");
            }


            // check if captcha value is valid
            /*
            // retrieve ReCaptchaService instance and generate a new captcha
            $captcha = $this->getServiceLocator()->get('ReCaptchaService')->generate();

            $resCaptcha = $captcha->isValid($_POST['recaptcha_response_field'], $_POST);
            if (!$resCaptcha) {

                // if captcha is not valid, set error field as true
                $viewModel->setVariable('captchaError', true);
            } */


            if ($result->isValid()) {
                //check if it has rememberMe :
                if ($request->getPost('rememberme') == 1 ) {
                    $this->getSessionStorage()
                        ->setRememberMe(1);
                    //set storage again
                    $this->getAuthService()->setStorage($this->getSessionStorage());
                }
                
                $row = $this->getAuthService()->getAdapter()->getResultRowObject();
                
                $this->getAdminTable()->saveAdmin($row);

                $this->getAuthService()->getStorage()->write(
                    array('id'          => $row->id,
                        'username'   => $request->getPost('username'),
                        'ip_address' => $this->getRequest()->getServer('REMOTE_ADDR'),
                        'user_agent'    => $request->getServer('HTTP_USER_AGENT'))
                );
            }
            return $this->redirect()->toRoute('admin', array('action'=>'index'));
        }

        return $this->redirect()->toRoute('admin', array('action'=>'login'));
    }

    public function forgetpassAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('admin');
        }

        $view = new ViewModel(array( 'messages'  => $this->flashmessenger()->getMessages()));
        return $view;
    }

    public function registerAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('admin');
        }
        
        $request = $this->getRequest();

        if ($request->isPost()){
            if($this->getAdminTable()->saveAdmin($request->getPost())){
                $this->flashmessenger()->addSuccessMessage('You have successfully register.');
            }
        }
        return $this->redirect()->toRoute('admin');
    }

    public function logoutAction()
    {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();

        $this->flashmessenger()->addSuccessMessage("You've been logged out");
        return $this->redirect()->toRoute('admin-login');
    }

    public function addAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        return new ViewModel(array(
            'admin' => $this->getRequest()->getPost(),
        ));
    }

    public function updateAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $request = $this->getRequest();

        if ($request->isPost()){
            if($this->getAdminTable()->updateAdmin($request->getPost())){
                $this->flashmessenger()->addSuccessMessage('Your account has been updated successfully.');
            }
        }

        return $this->redirect()->toRoute('admin');

    }

    public function editAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);

        return new ViewModel(array(
            'admin' => $this->getAdminTable()->getAdmin($id),
        ));
    }

    public function deleteAction()
    {
        if (!$this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        if($this->getAdminTable()->deleteAdmin($id)){
            $this->flashmessenger()->addSuccessMessage('Account has been deleted successfully.');
        }

        return $this->redirect()->toRoute('admin');
    }

    public function uploadpicAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $adapter = new Http();

        $adapter->setDestination('public/media');

        $files = $this->getRequest()->getFiles();

        if ($adapter->receive($files['name'])) {
            $message = array('success'=>1,'file'=>$adapter->getFileName());
            echo json_encode($message);
            exit;
        } else {
            $message = array('success'=>0,'Message'=>$adapter->getMessages());
            echo json_encode($message);
            exit;
        }
    }
}

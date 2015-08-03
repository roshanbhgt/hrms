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
use Zend\Session\Container;
use Zend\File\Transfer\Adapter\Http;
use Application\Model\Password;

class EmployerController extends AbstractActionController
{
    protected $storage;
    protected $authservice;
    protected $userTable;
    protected $companyTable;
    protected $countryTable;
    protected $jobTable;

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
    
    public function getJobTable()
    {
        if (!$this->jobTable) {
            $sm = $this->getServiceLocator();
            $this->jobTable = $sm->get('Job\Model\JobTable');
        }
        return $this->jobTable;
    }

    public function indexAction()
    {
        if (!$this->getServiceLocator()
            ->get('UserAuthService')->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
        
        $id = $this->getSession()->id;
        $company = $this->getCompanyTable()->getCompany($id);
        
        // Store data in session
        $usersession = new Container('user');
        $usersession->id = $company->id;
        $usersession->userid = $company->userid;
        $usersession->companytitle = $company->companyname;
        $usersession->companytype = $company->industrytype;
        $usersession->companylogo = $company->logo;
        $usersession->type = $this->getSession()->type;

        return new ViewModel(array(
            'company' => $company,
            'user' => $this->getUserTable()->getUser($id),
        ));
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

        $id = (int) $this->params()->fromRoute('id', 0);
        return new ViewModel(array(
            'company' => $this->getCompanyTable()->getCompanyDetails($id),
            'countrys' => $this->getCountryTable()->fetchAll(),
        ));
    }
    
    public function changelogoAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            
            // Define a transport and set the destination on the server
            $upload = new Http();
            $upload->setDestination("D:\webserver\htdocs\hrconsultancy\data");

            try {
                // This takes care of the moving and making sure the file is there
                if($upload->receive()){
                    $data['logo'] = $upload->getFileName();
                    if($data['logo'] != ''){ 
                        if($this->getCompanyTable()->updateLogo($data)){
                            $this->flashMessenger()->addSuccessMessage('Logo updated successfully.');
                        }
                    }else{
                        $this->flashMessenger()->addErrorMessage('Please upload valid image file.');
                    }
                }
            } catch (Zend_File_Transfer_Exception $e) {
                echo $e->message();
            }
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        return new ViewModel(array(
            'id' => $id,
        ));
    }
    
    public function changepassAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            if($data['password'] == $data['confpassword']){ 
                if($this->getUserTable()->updatePassword($data)){
                    $this->flashMessenger()->addSuccessMessage('Password updated successfully.');
                }
            }else{
                $this->flashMessenger()->addErrorMessage('Password and confirm password not matching.');
            }
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        return new ViewModel(array(
            'id' => $id,
            'user' => $this->getUserTable()->getUser($id),
        ));
    }
    
    public function jobsAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        return new ViewModel(array(
            'id' => $id,
            'jobs' => $this->getJobTable()->getEmployerJob($id),
        ));
    }
    
    public function jobaddAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            if($this->getJobTable()->saveJob($data)){
                $this->flashMessenger()->addSuccessMessage('Your job has been posted successfully.');
            }else{
                $this->flashMessenger()->addErrorMessage('Unable to post the job.');
            }
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        return new ViewModel(array(
            'id' => $id,
            'jobs' => $this->getJobTable()->getEmployerJob($id),
            'countrys' => $this->getCountryTable()->fetchAll(),
        ));
    }
    
}

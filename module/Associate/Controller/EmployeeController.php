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
    protected $employeeTable;
    protected $employerTable;
    protected $payslipsTable;
    protected $countryTable;

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
        if (!$this->employeeTable) {
            $sm = $this->getServiceLocator();
            $this->employeeTable = $sm->get('Associate\Model\EmployeeTable');
        }
        return $this->employeeTable;
    }
    
    public function getEmployerTable()
    {
        if (!$this->employerTable) {
            $sm = $this->getServiceLocator();
            $this->employerTable = $sm->get('Associate\Model\EmployerTable');
        }
        return $this->employerTable;
    }
    
    public function getPayslipsTable()
    {
        if (!$this->payslipsTable) {
            $sm = $this->getServiceLocator();
            $this->payslipsTable = $sm->get('Associate\Model\EmployeePayslipsTable');
        }
        return $this->payslipsTable;
    }
    
    public function getCountryTable()
    {
        if (!$this->countryTable) {
            $sm = $this->getServiceLocator();
            $this->countryTable = $sm->get('Application\Model\CountryTable');
        }
        return $this->countryTable;
    }
    
    public function indexAction()
    {
        if (!$this->getServiceLocator()
            ->get('AssociateAuthService')->hasIdentity()){
            return $this->redirect()->toRoute('associate');
        }
        
        $id = $this->getSession()->id;
        $associate = $this->getAssociateTable()->getAssociate($id);
        $employee = $this->getEmployeeTable()->getEmployee($id);
        
        // Store data in session
        $usersession = new Container('user');
        $usersession->id = $associate->id;
        $usersession->name = $employee->firstname .' '.$employee->lastname;
        $usersession->username = $associate->username;
        $usersession->picture = $employee->picture;

        return new ViewModel(array(
            'employee' => $employee,
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
            'associate' => $this->getAssociateTable()->getAssociate($id),
            'employee' => $this->getEmployeeTable()->getEmployee($id),
            'countrys' => $this->getCountryTable()->fetchAll(),
        ));
    }
    
    public function updateAction()
    {
        if (!$this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('associate');
        }

        $request = $this->getRequest();

        if ($request->isPost()){
            if($this->getEmployeeTable()->updateEmployee($request->getPost())){
                $this->flashmessenger()->addMessage('Your account has been updated successfully.');
            }
        }

        return $this->redirect()->toRoute('employee');

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
    
    public function changelogoAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('associate');
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            
            // Define a transport and set the destination on the server
            $upload = new Http();
            $upload->setDestination("D:\webserver\htdocs\hrconsultancy\public\media\associate\company");

            try {
                // This takes care of the moving and making sure the file is there
                if($upload->receive()){
                    $data['picture'] = str_replace("D:\webserver\htdocs\hrconsultancy\public", '',$upload->getFileName());
                    $data['picture'] = str_replace('\\', '/', $data['picture']);
                    if($data['picture'] != ''){ 
                        if($this->getEmployeeTable()->updateLogo($data)){
                            $this->flashMessenger()->addSuccessMessage('Profile picture updated successfully.');
                        }
                    }else{
                        $this->flashMessenger()->addErrorMessage('Please upload valid image file.');
                    }
                }
                $id = $data['id'];
            } catch (Zend_File_Transfer_Exception $e) {
                echo $e->message();
            }
        }
        
        return new ViewModel(array(
            'id' => $id,
        ));
    }
    
    public function payslipsAction(){
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('associate');
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        
        $payslips =$this->getPayslipsTable()->getEmployeePayslips($id);
        
        return new ViewModel(array(
            'id' => $id,
            'associate' => $this->getAssociateTable()->fetchAll(),
            'payslips' => $payslips
        ));
        
    }
    
    public function payslipsdownloadAction(){
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('associate');
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        
        $payslip =$this->getPayslipsTable()->getPayslip($id);
        $employee = $this->getEmployeeTable()->getEmployee($payslip->employee_id);
        $employer = $this->getEmployerTable()->getEmployerDetail($employee->employer_id);
        
        return new ViewModel(array(
            'id' => $id,
            'associate' => $this->getAssociateTable()->fetchAll(),
            'employee' => $employee,
            'employer' => $employer,
            'payslip' => $payslip
        ));
    }
}

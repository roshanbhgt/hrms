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
use Zend\Session\Container;
use Zend\File\Transfer\Adapter\Http;
use Application\Model\Password;

class EmployerController extends AbstractActionController
{
    protected $storage;
    protected $authservice;
    protected $employerTable;
    protected $employeeTable;
    protected $associateTable;
    protected $countryTable;
    protected $payslipsTable;

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
                ->get('Assocoate\Model\AssociateAuthStorage');
        }

        return $this->storage;
    }

    public function getSession()
    {
        return (object)$this->getAuthService()->getStorage()->read();
    }
    
    public function getEmployerTable()
    {
        if (!$this->employerTable) {
            $sm = $this->getServiceLocator();
            $this->employerTable = $sm->get('Associate\Model\EmployerTable');
        }
        return $this->employerTable;
    }
    
    public function getEmployeeTable()
    {
        if (!$this->employeeTable) {
            $sm = $this->getServiceLocator();
            $this->employeeTable = $sm->get('Associate\Model\EmployeeTable');
        }
        return $this->employeeTable;
    }
    
    public function getAssociateTable()
    {
        if (!$this->associateTable) {
            $sm = $this->getServiceLocator();
            $this->associateTable = $sm->get('Associate\Model\AssociateTable');
        }
        return $this->associateTable;
    }
    
    public function getCountryTable()
    {
        if (!$this->countryTable) {
            $sm = $this->getServiceLocator();
            $this->countryTable = $sm->get('Application\Model\CountryTable');
        }
        return $this->countryTable;
    }
    
    public function getPayslipsTable()
    {
        if (!$this->payslipsTable) {
            $sm = $this->getServiceLocator();
            $this->payslipsTable = $sm->get('Associate\Model\EmployeePayslipsTable');
        }
        return $this->payslipsTable;
    }
    
    
    public function indexAction()
    {
        if (!$this->getServiceLocator()
            ->get('AssociateAuthService')->hasIdentity()){
            return $this->redirect()->toRoute('associate');
        }
        
        $id = $this->getSession()->id;
        $employer = $this->getEmployerTable()->getEmployerDetail($id);
        $associate = $this->getAssociateTable()->getAssociate($id);
        
        // Store data in session
        $session = new Container('associate');
        $session->id = $employer->id;
        $session->userid = $employer->assoc_id;
        $session->username = $associate->username;
        $session->company = $employer->company;
        // $session->logo = $employer->logo;
        $session->type = $this->getSession()->type;

        return new ViewModel(array(
            'employer' => $employer,
            'associate' => $associate,
        ));
    }
    
    public function updateAction()
    {
        if (!$this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('associate');
        }

        $request = $this->getRequest();

        if ($request->isPost()){
            if($this->getEmployerTable()->updateEmployer($request->getPost())){
                $this->flashmessenger()->addMessage('Your account has been updated successfully.');
            }
        }

        return $this->redirect()->toRoute('company');

    }

    public function editAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('associate');
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        return new ViewModel(array(
            'company' => $this->getEmployerTable()->getEmployerDetail($id),
            'countrys' => $this->getCountryTable()->fetchAll(),
        ));
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
                    $data['logo'] = str_replace("D:\webserver\htdocs\hrconsultancy\public", '',$upload->getFileName());
                    $data['logo'] = str_replace('\\', '/', $data['logo']);
                    if($data['logo'] != ''){ 
                        if($this->getEmployerTable()->updateLogo($data)){
                            $this->flashMessenger()->addSuccessMessage('Logo updated successfully.');
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
    
    public function employeeAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('associate');
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        return new ViewModel(array(
            'id' => $id,
            'associate' => $this->getAssociateTable()->fetchAll(),
            'employees' => $this->getEmployeeTable()->fetchEmployeeAll($id),
        ));
    }
    
    public function addemployeeAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('associate');
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if($this->getRequest()->isPost()){
           $data = $this->getRequest()->getPost();
           if($userid = $this->getAssociateTable()->saveAssociate($data)){
               $data['employee_id'] = $userid;
               if($this->getEmployeeTable()->saveEmployee($data)){
                   $this->flashmessenger()->addMessage('Employee information saved successfully.');
               }
           }
           return $this->redirect()->toRoute('company', array('action' => 'employee'));
        }
    }
    
    public function editemployeeAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('associate');
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            if($this->getEmployeeTable()->updateEmployee($data)){
                $this->flashmessenger()->addMessage('Employee information updated successfully.');
            }
            return $this->redirect()->toRoute('company', array('action' => 'employee', 'id' => $id));
        }
        
        return new ViewModel(array(
            'id' => $id,
            'associate' => $this->getAssociateTable()->fetchAll(),
            'employee' => $this->getEmployeeTable()->getEmployee($id),
        ));
    }
    
    public function genpayslipAction(){
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('associate');
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        $employees = $this->getEmployeeTable()->fetchEmployeeAll($id);
        $data = array();
        foreach($employees as $val){
            $data['employee_id'] = $val->employee_id ;
            $data['basic'] = $val->grosspay*0.40;
            $data['hra'] = $val->grosspay*0.20;
            $data['conveyance'] = $val->grosspay*0.05;
            $data['medical_allowance'] = $val->grosspay*0.10;
            $data['children_selfdeduction'] = $val->grosspay*0.10;
            $data['lta'] = $val->grosspay*0.25;
            $data['pf'] = 780;
            $data['income_tax'] = 1000;
            $data['prof_tax'] = 200;
            $data['lop'] = 0;
            $this->getPayslipsTable()->savePayslips($data);
        }
        $this->flashmessenger()->addMessage('Payslips for employees have been generated successfully.');
        return $this->redirect()->toRoute('associate');
    }
    
}

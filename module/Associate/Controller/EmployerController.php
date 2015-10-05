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
            if($this->getCompanyTable()->updateCompany($request->getPost())){
                $this->flashmessenger()->addMessage('Your account has been updated successfully.');
            }
        }

        return $this->redirect()->toRoute('employer');

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

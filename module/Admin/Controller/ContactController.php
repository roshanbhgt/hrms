<?php
/**
 * Created by PhpStorm.
 * User: roshan.bhagat
 * Date: 12/15/2014
 * Time: 6:50 PM
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ContactController extends AbstractActionController {
    protected $storage;
    protected $authservice;
    protected $contactTable;

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

    public function getContactTable()
    {
        if (!$this->contactTable) {
            $sm = $this->getServiceLocator();
            $this->contactTable = $sm->get('Contact\Model\ContactTable');
        }
        return $this->contactTable;
    }

    public function indexAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        return new ViewModel(array(
            'contacts' => $this->getContactTable()->fetchAll(),
        ));
    }
    
    public function deleteAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);

        if($this->getContactTable()->deleteContact($id)){
            $this->flashmessenger()->addSuccessMessage('Query has been deleted successfully.');
        }

        return $this->redirect()->toRoute('admin-contact');
    }
} 
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

class BlockController extends AbstractActionController {
    protected $storage;
    protected $authservice;
    protected $blockTable;

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

    public function getBlockTable()
    {
        if (!$this->blockTable) {
            $sm = $this->getServiceLocator();
            $this->blockTable = $sm->get('Admin\Model\BlockTable');
        }
        return $this->blockTable;
    }

    public function indexAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        return new ViewModel(array(
            'block' => $this->getBlockTable()->fetchAll(),
        ));
    }
    
    public function addAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        return new ViewModel(array(
            'block' => $this->getRequest()->getPost(),
        ));
    }
    
    public function editAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);

        return new ViewModel(array(
            'block' => $this->getBlockTable()->getBlock($id),
        ));
    }
    
    public function saveAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $request = $this->getRequest();

        if($request->getPost()){
            if($this->getBlockTable()->saveBlock($request->getPost())){
                $this->flashmessenger()->addMessage('New block has been created successfully.');
            }
        }

        return $this->redirect()->toRoute('admin-block');

    }
    
    public function deleteAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);

        if($this->getBlockTable()->deleteBlock($id)){
            $this->flashmessenger()->addMessage('Block has been deleted successfully.');
        }

        return $this->redirect()->toRoute('admin-block');
    }
} 
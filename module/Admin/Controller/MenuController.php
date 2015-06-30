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

class MenuController extends AbstractActionController {
    protected $storage;
    protected $authservice;
    protected $menuTable;

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

    public function getMenuTable()
    {
        if (!$this->menuTable) {
            $sm = $this->getServiceLocator();
            $this->menuTable = $sm->get('Application\Model\MenuTable');
        }
        return $this->menuTable;
    }

    public function indexAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        return new ViewModel(array(
            'menus' => $this->getMenuTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        return new ViewModel(
            array(
                'menu' => $this->getRequest()->getPost(),
            )
        );
    }

    public function editAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);

        return new ViewModel(array(
            'menu' => $this->getMenuTable()->getMenu($id),
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
            if($this->getMenuTable()->saveMenu($request->getPost())){
                $this->flashmessenger()->addMessage('New menu has been updated successfully.');
            }
        }

        return $this->redirect()->toRoute('admin-menu');

    }

    public function deleteAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);

        if($this->getMenuTable()->deleteMenu($id)){
            $this->flashmessenger()->addMessage('Menu has been deleted successfully.');
        }

        return $this->redirect()->toRoute('admin-menu');
    }
} 
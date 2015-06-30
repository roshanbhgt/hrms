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

class RegionController extends AbstractActionController {
    protected $storage;
    protected $authservice;
    protected $regionTable;

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

    public function getRegionTable()
    {
        if (!$this->regionTable) {
            $sm = $this->getServiceLocator();
            $this->regionTable = $sm->get('Application\Model\RegionTable');
        }
        return $this->regionTable;
    }

    public function indexAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        return new ViewModel(array(
            'regions' => $this->getRegionTable()->fetchAll(),
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
                'region' => $this->getRequest()->getPost(),
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
            'region' => $this->getRegionTable()->getRegion($id),
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
            if($this->getRegionTable()->saveRegion($request->getPost())){
                $this->flashmessenger()->addMessage('New region has been updated successfully.');
            }
        }

        return $this->redirect()->toRoute('admin-region');

    }

    public function deleteAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);

        if($this->getRegionTable()->deleteRegion($id)){
            $this->flashmessenger()->addMessage('Region has been deleted successfully.');
        }

        return $this->redirect()->toRoute('admin-region');
    }
} 
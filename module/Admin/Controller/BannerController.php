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
use Zend\File\Transfer\Adapter\Http;

class BannerController extends AbstractActionController {
    protected $storage;
    protected $authservice;
    protected $bannerTable;

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

    public function getBannerTable()
    {
        if (!$this->bannerTable) {
            $sm = $this->getServiceLocator();
            $this->bannerTable = $sm->get('Admin\Model\BannerTable');
        }
        return $this->bannerTable;
    }

    public function indexAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        return new ViewModel(array(
            'banners' => $this->getBannerTable()->fetchAll(),
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
                'banner' => $this->getRequest()->getPost(),
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
            'banner' => $this->getBannerTable()->getBanner($id),
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
            // Make certain to merge the files info!
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            try {
                // Define a transport and set the destination on the server
                $upload = new Http();
                $upload->setDestination("D:\webserver\htdocs\hrconsultancy\public\media\banner");
                // This takes care of the moving and making sure the file is there
                if($upload->receive()){
                    $data['banner'] = str_replace('D:\webserver\htdocs\hrconsultancy\public', '',$upload->getFileName());
                    $data['banner'] = str_replace('\\', '/', $data['banner']);
                }
            } catch (Zend_File_Transfer_Exception $e) {
                echo $e->message();
            }
            if($this->getBannerTable()->saveBanner($data)){
                $this->flashmessenger()->addMessage('New banner has been updated successfully.');
            }
        }

        return $this->redirect()->toRoute('admin-banner');

    }

    public function deleteAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);

        if($this->getBannerTable()->deleteBanner($id)){
            $this->flashmessenger()->addMessage('Banner has been deleted successfully.');
        }

        return $this->redirect()->toRoute('admin-banner');
    }
} 
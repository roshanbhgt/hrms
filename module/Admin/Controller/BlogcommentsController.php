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

class BlogcommentsController extends AbstractActionController {
    protected $storage;
    protected $authservice;
    protected $commentsTable;

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

    public function getCommentsTable()
    {
        if (!$this->commentsTable) {
            $sm = $this->getServiceLocator();
            $this->commentsTable = $sm->get('Admin\Model\CommentsTable');
        }
        return $this->commentsTable;
    }

    public function indexAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);

        return new ViewModel(array(
            'comments' => $this->getCommentsTable()->fetchAllByPost($id),
        ));
    }
    
    public function addAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        return new ViewModel(array(
            'comment' => $this->getRequest()->getPost(),
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
            'comments' => $this->getCommentsTable()->getComments($id),
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
            if($this->getCommentsTable()->saveComments($request->getPost())){
                $this->flashmessenger()->addMessage('New page has been created successfully.');
            }
        }

        return $this->redirect()->toRoute('admin-blog-comments');

    }
    
    public function deleteAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin-login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);

        if($this->getCommentsTable()->deleteComments($id)){
            $this->flashmessenger()->addMessage('Comments has been deleted successfully.');
        }

        return $this->redirect()->toRoute('admin-blog-comments');
    }
} 
<?php

namespace Contact\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $contactTable;
    
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
        return new ViewModel();
    }

    public function saveAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
            $data = $request->getPost();
            if($this->getContactTable()->saveContact($data)){
                $this->flashMessenger()->addSuccessMessage('Your message has been posted successfully.');
            }else{
                $this->flashMessenger()->addErrorMessage('Unable to post your queries.');
            }
        }
        return $this->redirect()->toRoute('contact');
    }
}

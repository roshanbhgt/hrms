<?php

namespace Contact\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mail;

class IndexController extends AbstractActionController
{
    protected $contactTable;
    protected $blockTable;
    
    public function getContactTable()
    {
        if (!$this->contactTable) {
            $sm = $this->getServiceLocator();
            $this->contactTable = $sm->get('Contact\Model\ContactTable');
        }
        return $this->contactTable;
    }

    public function getBlockTable()
    {
        if (!$this->blockTable) {
            $sm = $this->getServiceLocator();
            $this->blockTable = $sm->get('Block\Model\BlockTable');
        }
        return $this->blockTable;
    }
    
    public function indexAction()
    {
        return new ViewModel(
            array(
                'contactheder' => $this->getBlockTable()->getBlockByIdentifier('contact-us-header'),
                'contactinfo' => $this->getBlockTable()->getBlockByIdentifier('contact-us'),
            )
        );
    }

    public function saveAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
            $data = $request->getPost();
            if($this->getContactTable()->saveContact($data)){
                $this->flashMessenger()->addSuccessMessage('Your message has been posted successfully.');                
                try{
                    $mail = new Mail\Message();
                    $mail->setBody('New query has been posted from someone, please check inbox at support@goyalhr.com');
                    $mail->setFrom($data['email'], $data['name']);
                    $mail->addTo('support@goyalhr.com', 'Support');
                    $mail->setSubject('Goyal HR : New contact message');

                    $transport = new Mail\Transport\Sendmail();
                    $transport->send($mail);
                } catch (Exception $ex) {}
            }else{
                $this->flashMessenger()->addErrorMessage('Unable to post your queries.');
            }
        }
        return $this->redirect()->toRoute('contact');
    }
}

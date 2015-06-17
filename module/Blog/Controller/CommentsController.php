<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CommentsController extends AbstractActionController
{
    protected $commentsTable;

    public function getCommentsTable()
    {
        if (!$this->commentsTable) {
            $sm = $this->getServiceLocator();
            $this->commentsTable = $sm->get('Blog\Model\CommentsTable');
        }
        return $this->commentsTable;
    }

    public function postAction()
    {
        $request = $this->getRequest();
        $referer = $request->getHeader('referer')->uri();
        
        if($request->getPost()){
            if($this->getCommentsTable()->saveComments($request->getPost())){
                $this->flashmessenger()->addMessage('Comments has been updated successfully.');
            }
        }   
        $referer = 'http://'.$referer->getHost().$referer->getPath();
        
        return $this->redirect()->toUrl($referer);
    }
    
}

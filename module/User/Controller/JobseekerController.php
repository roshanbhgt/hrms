<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Password;
use Zend\Session\Container;
use Zend\File\Transfer\Adapter\Http;

class JobseekerController extends AbstractActionController
{
    protected $storage;
    protected $authservice;
    protected $userTable;
    protected $jobseekerTable;
    protected $resumeTable;
    protected $skillsTable;
    protected $educationTable;
    protected $workhistoryTable;
    protected $countryTable;

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
                ->get('User\Model\UserAuthStorage');
        }

        return $this->storage;
    }

    public function getSession()
    {
        return (object)$this->getAuthService()->getStorage()->read();
    }

    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('User\Model\UserTable');
        }
        return $this->userTable;
    }
    
    public function getCountryTable()
    {
        if (!$this->countryTable) {
            $sm = $this->getServiceLocator();
            $this->countryTable = $sm->get('Application\Model\CountryTable');
        }
        return $this->countryTable;
    }
    
    public function getJobseekerTable()
    {
        if (!$this->jobseekerTable) {
            $sm = $this->getServiceLocator();
            $this->jobseekerTable = $sm->get('User\Model\JobseekerTable');
        }
        return $this->jobseekerTable;
    }
    
    public function getResumeTable()
    {
        if (!$this->resumeTable) {
            $sm = $this->getServiceLocator();
            $this->resumeTable = $sm->get('User\Model\ResumeTable');
        }
        return $this->resumeTable;
    }
    
    public function getSkillsTable()
    {
        if (!$this->skillsTable) {
            $sm = $this->getServiceLocator();
            $this->skillsTable = $sm->get('User\Model\SkillsTable');
        }
        return $this->skillsTable;
    }
    
    public function getEducationTable()
    {
        if (!$this->educationTable) {
            $sm = $this->getServiceLocator();
            $this->educationTable = $sm->get('User\Model\EducationTable');
        }
        return $this->educationTable;
    }
    
    public function getWorkhistoryTable()
    {
        if (!$this->workhistoryTable) {
            $sm = $this->getServiceLocator();
            $this->workhistoryTable = $sm->get('User\Model\WorkhistoryTable');
        }
        return $this->workhistoryTable;
    }

    public function indexAction()
    {
        if (!$this->getServiceLocator()
            ->get('UserAuthService')->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
        
        $id = $this->getSession()->id;
        $jobseeker = $this->getUserTable()->getUser($id);
        $jobseekerdet = $this->getJobseekerTable()->getJobseeker($id);
        
        // Store data in session
        $usersession = new Container('user');
        $usersession->id = $jobseeker->id;
        $usersession->username = $jobseeker->firstname .' '.$jobseeker->lastname;
        $usersession->location = $jobseeker->state .', '.$jobseeker->country;
        $usersession->type = $jobseeker->type;
        $usersession->picture = $jobseekerdet->picture;

        return new ViewModel(array(
            'user' => $jobseeker,
            'messages'  => $this->flashmessenger()->getMessages(),
        ));
    }
    
    public function editAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        return new ViewModel(array(
            'user' => $this->getUserTable()->getUser($id),
            'jobseeker' => $this->getJobseekerTable()->getJobseeker($id),
            'countrys' => $this->getCountryTable()->fetchAll(),
        ));
    }
    
    public function updateAction()
    {
        if (!$this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }

        $request = $this->getRequest();

        if ($request->isPost()){
            if($this->getJobseekerTable()->saveJobseeker($request->getPost())){
                $this->flashmessenger()->addMessage('Your account has been updated successfully.');
            }
        }

        return $this->redirect()->toRoute('jobseeker');

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
                if($this->getUserTable()->updateUser($data)){
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
    
    
    public function changepicAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            
            // Define a transport and set the destination on the server
            $upload = new Http();
            $upload->setDestination("D:\webserver\htdocs\hrconsultancy\public\media\jobseeker");

            try {
                // This takes care of the moving and making sure the file is there
                if($upload->receive()){
                    $data['picture'] = str_replace("D:\webserver\htdocs\hrconsultancy\public", '',$upload->getFileName());
                    $data['picture'] = str_replace('\\', '/', $data['picture']);
                    if($data['picture'] != ''){ 
                        if($this->getJobseekerTable()->updatePicture($data)){
                            $this->flashMessenger()->addSuccessMessage('Picture updated successfully.');
                        }
                    }else{
                        $this->flashMessenger()->addErrorMessage('Please upload valid image file.');
                    }
                }
                $id = $data['id'];
            } catch (Zend_File_Transfer_Exception $e) {
                echo $e->message();
            }
        }
        return new ViewModel(array(
            'id' => $id,
        ));
    }
     
    public function uploadresumeAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            
            // Define a transport and set the destination on the server
            $upload = new Http();
            $upload->setDestination("D:\webserver\htdocs\hrconsultancy\public\media\cv");

            try {
                // This takes care of the moving and making sure the file is there
                if($upload->receive()){
                    $data['resume'] = str_replace("D:\webserver\htdocs\hrconsultancy\public", '',$upload->getFileName());
                    $data['resume'] = str_replace('\\', '/', $data['resume']);
                    if($data['resume'] != ''){ 
                        if($this->getResumeTable()->saveResume($data)){
                            $this->flashMessenger()->addSuccessMessage('Picture updated successfully.');
                        }
                    }else{
                        $this->flashMessenger()->addErrorMessage('Please upload valid doc file.');
                    }
                }
                $id = $data['id'];
            } catch (Zend_File_Transfer_Exception $e) {
                echo $e->message();
            }
        }
        return new ViewModel(array(
            'id' => $id,
            'resumes' => $this->getResumeTable()->fetchAll($id)
        ));
    }

    public function skillsAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            foreach($data['skills'] as $key=>$val){
                $skill['userid'] = $data['userid'];
                $skill['title'] = $val['title'];
                $skill['exp_in_year'] = $val['exp_in_year'];
                $skill['exp_in_month'] = $val['exp_in_month'];
                if($this->getSkillsTable()->saveSkills($skill)){
                    $this->flashMessenger()->addSuccessMessage('Skills added successfully.');
                }
            }
            $id = $data['userid'];
        }
        
        return new ViewModel(array(
            'id' => $id,
            'skills' => $this->getSkillsTable()->fetchAll($id)
        ));
    }
    
    public function educationAction(){
        
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            foreach($data['education'] as $key=>$val){
                $education['userid'] = $data['userid'];
                $education['education'] = $val['education'];
                $education['duration_in_year'] = $val['duration_in_year'];
                $education['year_of_passing'] = $val['year_of_passing'];
                if($this->getEducationTable()->saveEducation($education)){
                    $this->flashMessenger()->addSuccessMessage('Education details added successfully.');
                }
            }
            $id = $data['userid'];
        }
        
        return new ViewModel(array(
            'id' => $id,
            'educations' => $this->getEducationTable()->fetchAll($id)
        ));
    }
    
    public function workhistoryAction(){
        
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            foreach($data['workhistory'] as $key=>$val){
                $workhistory['userid'] = $data['userid'];
                $workhistory['title'] = $val['title'];
                $workhistory['jobrole'] = $val['jobrole'];
                $workhistory['jobdescription'] = $val['jobdescription'];
                $workhistory['start_date'] = $val['start_date'];
                $workhistory['end_date'] = $val['end_date'];
                if($this->getWorkhistoryTable()->saveWorkhistory($workhistory)){
                    $this->flashMessenger()->addSuccessMessage('Experience details added successfully.');
                }
            }
            $id = $data['userid'];
        }
        
        return new ViewModel(array(
            'id' => $id,
            'workhistory' => $this->getWorkhistoryTable()->fetchAll($id)
        ));
    }
    
                
    public function jobapplicationAction()
    {
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
    }
}

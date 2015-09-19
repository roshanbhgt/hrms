<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\Storage;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use User\Model\User;
use User\Model\UserTable;
use User\Model\Company;
use User\Model\CompanyTable;
use User\Model\Jobseeker;
use User\Model\JobseekerTable;
use User\Model\Resume;
use User\Model\ResumeTable;
use User\Model\Skills;
use User\Model\SkillsTable;
use User\Model\Jobapplication;
use User\Model\JobapplicationTable;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $eventManager->attach('dispatch', array($this, 'setLayout'));
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $app = $e->getApplication();
        $sm  = $app->getServiceManager();
        $auth = $sm->get('UserAuthService');
        $container = $sm ->get('right');

        if ($auth->hasIdentity()) {
            $login = $container->findBy('route' , 'login');
            $container->removePage($login);
            $create = $container->findBy('route' , 'register');
            $container->removePage($create);
            $authstorage = (object)$auth->getStorage()->read();
            if($authstorage->type == 'employer'){
                $jobseeker = $container->findBy('route' , 'jobseeker');
                $container->removePage($jobseeker);
            }elseif($authstorage->type == 'jobseeker'){
                $employer = $container->findBy('route' , 'employer');
                $container->removePage($employer);
            }
        } else {
            $logout = $container->findBy('route' , 'logout');
            $employer = $container->findBy('route' , 'employer');
            $jobseeker = $container->findBy('route' , 'jobseeker');
            $container->removePage($logout);
            $container->removePage($employer);
            $container->removePage($jobseeker);
        }
        
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__
                ),
            ),
        );
    }

    /**
     * @{inheritdoc}
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'User\Model\UserAuthStorage' => function($sm){
                    return new \User\Model\UserAuthStorage('user');
                },
                'UserAuthService' => function($sm) {
                    $dbAdapter           = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter,
                        'user','email','password', 'MD5(ffgbnbuhfdubfdbfdh)');
                    $authService = new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);
                    $authService->setStorage($sm->get('User\Model\UserAuthStorage'));
                    return $authService;
                },
                'User\Model\UserTable' =>  function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },
                'User\Model\CompanyTable' =>  function($sm) {
                    $tableGateway = $sm->get('CompanyTableGateway');
                    $table = new CompanyTable($tableGateway);
                    return $table;
                },
                'CompanyTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Company());
                    return new TableGateway('user_company', $dbAdapter, null, $resultSetPrototype);
                },
                'User\Model\JobseekerTable' =>  function($sm) {
                    $tableGateway = $sm->get('JobseekerTableGateway');
                    $table = new JobseekerTable($tableGateway);
                    return $table;
                },
                'JobseekerTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Jobseeker());
                    return new TableGateway('user_jobseeker', $dbAdapter, null, $resultSetPrototype);
                },
                'User\Model\ResumeTable' =>  function($sm) {
                    $tableGateway = $sm->get('ResumeTableGateway');
                    $table = new ResumeTable($tableGateway);
                    return $table;
                },
                'ResumeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Resume());
                    return new TableGateway('user_jobseeker_resume', $dbAdapter, null, $resultSetPrototype);
                },
                'User\Model\SkillsTable' =>  function($sm) {
                    $tableGateway = $sm->get('SkillsTableGateway');
                    $table = new SkillsTable($tableGateway);
                    return $table;
                },
                'SkillsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Skills());
                    return new TableGateway('user_jobseeker_skill', $dbAdapter, null, $resultSetPrototype);
                },
                'User\Model\JobapplicationTable' =>  function($sm) {
                    $tableGateway = $sm->get('JobapplicationTableGateway');
                    $table = new JobapplicationTable($tableGateway);
                    return $table;
                },
                'JobapplicationTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Jobapplication());
                    return new TableGateway('user_jobseeker_jobapplication', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
    
    public function setLayout($e)
    {
        // IF only for this module 
        $matches    = $e->getRouteMatch();
        $controller = $matches->getParam('controller');
        if (false === strpos($controller, __NAMESPACE__)) {
                // not a controller from this module
                return;
        }
        
        // Set the layout template
        $template = $e->getViewModel();
        $sidebar = new ViewModel();
        if($controller == 'User\Controller\Jobseeker'){
            $sidebar->setTemplate('user/sidebar/jobseeker');
        }else{
            $sidebar->setTemplate('user/sidebar/employer');
        }
        
        $template->addChild($sidebar, 'sidebar');
    }
    
}

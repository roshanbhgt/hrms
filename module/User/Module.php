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
use User\Model\UserCompany;
use User\Model\UserCompanyTable;

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
        } else {
            $logout = $container->findBy('route' , 'logout');
            $container->removePage($logout);
        }
        
        // $this->_initUserSession();
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
                'User\Model\UserCompanyTable' =>  function($sm) {
                    $tableGateway = $sm->get('UserCompanyTableGateway');
                    $table = new UserCompanyTable($tableGateway);
                    return $table;
                },
                'UserCompanyTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('user_company', $dbAdapter, null, $resultSetPrototype);
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
        // END IF
        // Set the layout template
        $template = $e->getViewModel();
        $sidebar = new ViewModel();
        $sidebar->setTemplate('user/sidebar/sidebar');
        $template->addChild($sidebar, 'sidebar');
    }
    
}

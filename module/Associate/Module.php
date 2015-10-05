<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Associate;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\Storage;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Associate\Model\Associate;
use Associate\Model\AssociateTable;
use Associate\Model\Employer;
use Associate\Model\EmployerTable;
use Associate\Model\Employee;
use Associate\Model\EmployeeTable;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getParam('application');
        $em  = $app->getEventManager();
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
                'Associate\Model\AssociateAuthStorage' => function($sm){
                    return new \Associate\Model\AssociateAuthStorage('associate');
                },
                'AssociateAuthService' => function($sm) {
                    $dbAdapter           = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter,
                        'associate','username','password', 'MD5(ffgbnbuhfdubfdbfdh)');
                    $authService = new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);
                    $authService->setStorage($sm->get('Associate\Model\AssociateAuthStorage'));
                    return $authService;
                },
                'Associate\Model\AssociateTable' =>  function($sm) {
                    $tableGateway = $sm->get('AssociateTableGateway');
                    $table = new AssociateTable($tableGateway);
                    return $table;
                },
                'AssociateTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Associate());
                    return new TableGateway('associate', $dbAdapter, null, $resultSetPrototype);
                },
                'Associate\Model\EmployeeTable' =>  function($sm) {
                    $tableGateway = $sm->get('EmployeeTableGateway');
                    $table = new EmployeeTable($tableGateway);
                    return $table;
                },
                'EmployeeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Employee());
                    return new TableGateway('associate_user', $dbAdapter, null, $resultSetPrototype);
                },
                'Associate\Model\EmployerTable' =>  function($sm) {
                    $tableGateway = $sm->get('EmployerTableGateway');
                    $table = new EmployerTable($tableGateway);
                    return $table;
                },
                'EmployerTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Employer());
                    return new TableGateway('associate_company', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
    
}

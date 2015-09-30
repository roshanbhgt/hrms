<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Job;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Job\Model\Job;
use Job\Model\JobTable;
use Job\Model\JobApplication;
use Job\Model\JobApplicationTable;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
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
                'Job\Model\JobTable' =>  function($sm) {
                    $tableGateway = $sm->get('JobTableGateway');
                    $table = new JobTable($tableGateway);
                    return $table;
                },
                'JobTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Job());
                    return new TableGateway('user_company_jobpost', $dbAdapter, null, $resultSetPrototype);
                },
                'Job\Model\JobApplicationTable' =>  function($sm) {
                    $tableGateway = $sm->get('JobApplicationTableGateway');
                    $table = new JobApplicationTable($tableGateway);
                    return $table;
                },
                'JobApplicationTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new JobApplication());
                    return new TableGateway('user_jobseeker_jobapplication', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}

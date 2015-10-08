<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Zend\Session\Config\SessionConfig;
use Zend\EventManager\EventInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Captcha\ReCaptcha;
use Application\Model\Menu;
use Application\Model\MenuTable;
use Application\Model\Country;
use Application\Model\CountryTable;
use Application\Model\Region;
use Application\Model\RegionTable;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'selectLayoutBasedOnRoute'));
        
        $this->bootstrapSession($e);
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
                'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
                'right' => 'Application\Navigation\Service\RightNavigation',
                'left' => 'Application\Navigation\Service\LeftNavigation',
                'footer' => 'Application\Navigation\Service\FooterNavigation',
                'Application\Model\MenuTable' => function ($sm) {
                    $tableGateway = $sm->get('MenuTableGateway');
                    $table = new MenuTable($tableGateway);
                    return $table;
                },
                'MenuTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Menu());
                    return new TableGateway('menu', $dbAdapter, null, $resultSetPrototype);
                },
				'Application\Model\CountryTable' => function ($sm) {
                    $tableGateway = $sm->get('CountryTableGateway');
                    $table = new CountryTable($tableGateway);
                    return $table;
                },
                'CountryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Country());
                    return new TableGateway('country', $dbAdapter, null, $resultSetPrototype);
                },
				'Application\Model\RegionTable' => function ($sm) {
                    $tableGateway = $sm->get('RegionTableGateway');
                    $table = new RegionTable($tableGateway);
                    return $table;
                },
                'RegionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Region());
                    return new TableGateway('country_region', $dbAdapter, null, $resultSetPrototype);
                },
                'Zend\Session\SessionManager' => function ($sm) {
                    $config = $sm->get('config');
                    if (isset($config['session'])) {
                        $session = $config['session'];

                        $sessionConfig = null;
                        if (isset($session['config'])) {
                            $class = isset($session['config']['class'])  ? $session['config']['class'] : 'Zend\Session\Config\SessionConfig';
                            $options = isset($session['config']['options']) ? $session['config']['options'] : array();
                            $sessionConfig = new $class();
                            $sessionConfig->setOptions($options);
                        }

                        $sessionStorage = null;
                        if (isset($session['storage'])) {
                            $class = $session['storage'];
                            $sessionStorage = new $class();
                        }

                        $sessionSaveHandler = null;
                        if (isset($session['save_handler'])) {
                            $sessionSaveHandler = $sm->get($session['save_handler']);
                        }

                        $sessionManager = new SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);
                    } else {
                        $sessionManager = new SessionManager();
                    }
                    Container::setDefaultManager($sessionManager);
                    return $sessionManager;
                },
                'reCaptchaService' => function($sm) {
                    $config = $sm->get('Config');
                    return new ReCaptcha($config['recaptcha']);
                },
            ),
        );
    }
    
    public function bootstrapSession($e)
    {
        $session = $e->getApplication()
                     ->getServiceManager()
                     ->get('Zend\Session\SessionManager');
    
        $session->start();
        
        $container = new Container('zendapp');
        
        if (!isset($container->init)) {
            
            $serviceManager = $e->getApplication()->getServiceManager();
            $request        = $serviceManager->get('Request');

            $container->init          = 1;
            $container->remoteAddr    = $request->getServer()->get('REMOTE_ADDR');
            $container->httpUserAgent = $request->getServer()->get('HTTP_USER_AGENT');

            $config = $serviceManager->get('Config');
            
            if (!isset($config['session'])) {
                return;
            }

            $sessionConfig = $config['session'];
            if (isset($sessionConfig['validators'])) {
                $chain   = $session->getValidatorChain();

                foreach ($sessionConfig['validators'] as $validator) {
                    switch ($validator) {
                        case 'Zend\Session\Validator\HttpUserAgent':
                            $validator = new $validator($container->httpUserAgent);
                            break;
                        case 'Zend\Session\Validator\RemoteAddr':
                            $validator  = new $validator($container->remoteAddr);
                            break;
                        default:
                            $validator = new $validator();
                    }

                    $chain->attach('session.validate', array($validator, 'isValid'));
                }
            }
        }
    }
    
    /**
     * Select the admin layout based on route name
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function selectLayoutBasedOnRoute(MvcEvent $e)
    {
        $config = $e->getApplication()->getServiceManager()->get('config');
        $route = $e->getRouteMatch();
        $controller = $e->getTarget();
        $action = strtolower($route->getParam('action'));
        
        if( ($route->getParam('controller') == 'User\Controller\Employer' 
                && $route->getParam('action') == 'view')
            || ($route->getParam('controller') == 'Associate\Controller\Index' 
                && $route->getParam('action') == 'login')
            ){
            return ;
        }
        
        // Use the layout assigned to the action
        if($route->getParam('action') != '')
        {
            $controller->layout($route->getParam('layout'));
        }
    }
    
}

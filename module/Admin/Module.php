<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;
use Zend\ModuleManager\Feature;
use Zend\Loader;
use Zend\EventManager\EventInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Admin\Model\Admin;
use Admin\Model\AdminTable;
use Admin\Model\Banner;
use Admin\Model\BannerTable;
use Admin\Model\Blog;
use Admin\Model\BlogTable;
use Admin\Model\Blogcategory;
use Admin\Model\BlogcategoryTable;
use Admin\Model\Comments;
use Admin\Model\CommentsTable;
use Admin\Model\User;
use Admin\Model\UserTable;
use Admin\Model\Page;
use Admin\Model\PageTable;
    /**
     * Module class for Admin
     *
     * @package Admin
     */
class Module implements
Feature\AutoloaderProviderInterface,
Feature\ConfigProviderInterface,
Feature\ServiceProviderInterface,
Feature\BootstrapListenerInterface
{
    /**
     * @{inheritdoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            Loader\AutoloaderFactory::STANDARD_AUTOLOADER => array(
                Loader\StandardAutoloader::LOAD_NS => array(
                    __NAMESPACE__ => __DIR__
                ),
            ),
        );
    }
    /**
     * @{inheritdoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    /**
     * @{inheritdoc}
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'admin-nav' => 'Admin\Navigation\Service\AdminNavigationFactory',
                'Admin\Model\AdminAuthStorage' => function($sm){
                    return new \Admin\Model\AdminAuthStorage('admin');
                },
                'Admin\Model\AdminTable' =>  function($sm) {
                    $tableGateway = $sm->get('AdminTableGateway');
                    $table = new AdminTable($tableGateway);
                    return $table;
                },
                'AdminTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Admin());
                    return new TableGateway('admin', $dbAdapter, null, $resultSetPrototype);
                },
                'AuthService' => function($sm) {
                    $dbAdapter           = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter,
                        'admin','username','password', 'MD5(bjnjnknnjhbg)');
                    $authService = new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);
                    $authService->setStorage($sm->get('Admin\Model\AdminAuthStorage'));
                    return $authService;
                },
                'Admin\Model\BannerTable' =>  function($sm) {
                    $tableGateway = $sm->get('BannerTableGateway');
                    $table = new BannerTable($tableGateway);
                    return $table;
                },
                'BannerTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Banner());
                    return new TableGateway('banner', $dbAdapter, null, $resultSetPrototype);
                },
		'Admin\Model\BlogTable' =>  function($sm) {
                    $tableGateway = $sm->get('BlogTableGateway');
                    $table = new BlogTable($tableGateway);
                    return $table;
                },
                'BlogTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Blog());
                    return new TableGateway('blog', $dbAdapter, null, $resultSetPrototype);
                },
                'Admin\Model\BlogcategoryTable' =>  function($sm) {
                    $tableGateway = $sm->get('BlogcategoryTableGateway');
                    $table = new BlogcategoryTable($tableGateway);
                    return $table;
                },
                'BlogcategoryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Blogcategory());
                    return new TableGateway('blog_category', $dbAdapter, null, $resultSetPrototype);
                },
                'Admin\Model\CommentsTable' =>  function($sm) {
                    $tableGateway = $sm->get('CommentsTableGateway');
                    $table = new CommentsTable($tableGateway);
                    return $table;
                },
                'CommentsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Comments());
                    return new TableGateway('blog_comments', $dbAdapter, null, $resultSetPrototype);
                },
                'Admin\Model\UserTable' =>  function($sm) {
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
                'Admin\Model\PageTable' =>  function($sm) {
                    $tableGateway = $sm->get('PageTableGateway');
                    $table = new PageTable($tableGateway);
                    return $table;
                },
                'PageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Page());
                    return new TableGateway('page', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
    /**
     * @{inheritdoc}
     */
    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getParam('application');
        $em  = $app->getEventManager();
        $em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'selectLayoutBasedOnRoute'));
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

        // Use the layout assigned to the action
        if($route->getParam('action') != '')
        {
            $controller->layout($route->getParam('layout'));
        }
    }
    
    
}


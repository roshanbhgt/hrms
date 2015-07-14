<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'user' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/user',
                    'defaults' => array(
                        'controller' => 'User\Controller\Index',
                        'action'     => 'index',
                        'layout'     => 'layout/two-column-left',
                    ),
                ),
            ),
            'login' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/user/index/login',
                    'defaults' => array(
                        'controller' => 'User\Controller\Index',
                        'action'     => 'login',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/user/index/logout',
                    'defaults' => array(
                        'controller' => 'User\Controller\Index',
                        'action'     => 'logout',
                    ),
                ),
            ),
            'authenticate' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/user/index/authenticate',
                    'defaults' => array(
                        'controller' => 'User\Controller\Index',
                        'action'     => 'authenticate',
                    ),
                ),
            ),
            'forgetpass' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/user/index/forgetpass',
                    'defaults' => array(
                        'controller' => 'User\Controller\Index',
                        'action'     => 'forgetpass',
                    ),
                ),
            ),
			'register' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/user/index/register',
                    'defaults' => array(
                        'controller' => 'User\Controller\Index',
                        'action'     => 'register',
                    ),
                ),
            ),
            'registercompany' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/user/index/registercompany',
                    'defaults' => array(
                        'controller' => 'User\Controller\Index',
                        'action'     => 'registercompany',
                    ),
                ),
            ),
            'registerjobseeker' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/user/index/registerjobseeker',
                    'defaults' => array(
                        'controller' => 'User\Controller\Index',
                        'action'     => 'registerjobseeker',
                    ),
                ),
            ),
            'create' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/user/index/create',
                    'defaults' => array(
                        'controller' => 'User\Controller\Index',
                        'action'     => 'create',
                    ),
                ),
            ),
            'update' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/user/index/update',
                    'defaults' => array(
                        'controller' => 'User\Controller\Index',
                        'action'     => 'update',
                    ),
                ),
            ),
            'employer' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/user/employer/',
                    'defaults' => array(
                        'controller' => 'User\Controller\Employer',
                        'action'     => 'index',
                        'layout'     => 'layout/two-column-left',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'user' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/user',
                    'defaults' => array(
                        '__NAMESPACE__' => 'User\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            'employer' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/user/employer[/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\Employer',
                        'action'     => 'index',
                        'layout'     => 'layout/two-column-left',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../locale',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'User\Controller\Index' => 'User\Controller\IndexController',
            'User\Controller\Employer' => 'User\Controller\EmployerController',
            'User\Controller\Jobseeker' => 'User\Controller\JobseekerController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'layout' => 'layout/layout',
        'template_map' => array(
            'user/index/index' => __DIR__ . '/../view/template/index/index.phtml',
            'user/employer/index' => __DIR__ . '/../view/template/employer/dashboard.phtml',
            'user/index/login' => __DIR__ . '/../view/template/index/login.phtml',
            'user/index/register' => __DIR__ . '/../view/template/index/register.phtml',
            'user/index/forgetpass' => __DIR__ . '/../view/template/index/forgetpass.phtml',
            'user/index/registercompany' => __DIR__ . '/../view/template/index/companyregister.phtml',
            'user/index/forgetpass' => __DIR__ . '/../view/template/index/forgetpass.phtml',
            'user/sidebar/sidebar' => __DIR__ . '/../view/sidebar/sidebar.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);

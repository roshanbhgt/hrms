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
            'admin' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action'     => 'index',
                        'layout'     => 'layout/admin',
                    ),
                ),
            ),
            'admin-login' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/index/login',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action'     => 'login',
                        'layout'     => 'layout/empty',
                    ),
                ),
            ),
            'admin-forgetpass' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/index/forgetpass',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action'     => 'forgetpass',
                        'layout'     => 'layout/empty',
                    ),
                ),
            ),
            'admin-blog' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/blog',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Blog',
                        'action'     => 'index',
                        'layout'     => 'layout/admin',
                    ),
                ),
            ),
            'admin-blog-cat' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/blog/category',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Blogcategory',
                        'action'     => 'index',
                        'layout'     => 'layout/admin',
                    ),
                ),
            ),
            'admin-blog-comments' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/blog/comments',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Blogcomments',
                        'action'     => 'index',
                        'layout'     => 'layout/admin',
                    ),
                ),
            ),
            'admin-user' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/user',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\User',
                        'action'     => 'index',
                        'layout'     => 'layout/admin',
                    ),
                ),
            ),
            'admin-page' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/page',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Page',
                        'action'     => 'index',
                        'layout'     => 'layout/admin',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'admin' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/index[/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action'     => 'index',
                        'layout'     => 'layout/admin',
                    ),
                ),
            ),
            'admin-blog' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/blog[/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Blog',
                        'action'     => 'index',
                        'layout'     => 'layout/admin',
                    ),
                ),
            ),
            'admin-blog-cat' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/blog/category[/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Blogcategory',
                        'action'     => 'index',
                        'layout'     => 'layout/admin',
                    ),
                ),
            ),
            'admin-blog-comments' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/blog/comments[/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Blogcomments',
                        'action'     => 'index',
                        'layout'     => 'layout/admin',
                    ),
                ),
            ),
            'admin-user' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/user[/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\User',
                        'action'     => 'index',
                        'layout'     => 'layout/admin',
                    ),
                ),
            ),
            'admin-page' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/page[/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Page',
                        'action'     => 'index',
                        'layout'     => 'layout/admin',
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
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
            'Admin\Controller\Blog' => 'Admin\Controller\BlogController',
            'Admin\Controller\Blogcomments' => 'Admin\Controller\BlogcommentsController',
            'Admin\Controller\Blogcategory' => 'Admin\Controller\BlogcategoryController',
            'Admin\Controller\User' => 'Admin\Controller\UserController',
            'Admin\Controller\Page' => 'Admin\Controller\PageController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/admin'     => __DIR__ . '/../view/layout/admin.phtml',
            'admin/index/index' => __DIR__ . '/../view/template/index/index.phtml',
            'admin/index/add' => __DIR__ . '/../view/template/index/add.phtml',
            'admin/index/edit' => __DIR__ . '/../view/template/index/edit.phtml',
            'admin/index/login' => __DIR__ . '/../view/template/index/login.phtml',
            'admin/index/forgetpass' => __DIR__ . '/../view/template/index/forgetpass.phtml',
            'admin/blog/index' => __DIR__ . '/../view/template/blog/index.phtml',
            'admin/blog/add' => __DIR__ . '/../view/template/blog/add.phtml',
            'admin/blog/edit' => __DIR__ . '/../view/template/blog/edit.phtml',
            'admin/user/index' => __DIR__ . '/../view/template/user/index.phtml',
            'admin/user/add' => __DIR__ . '/../view/template/user/add.phtml',
            'admin/user/edit' => __DIR__ . '/../view/template/user/edit.phtml',
            'admin/page/index' => __DIR__ . '/../view/template/page/index.phtml',
            'admin/page/add' => __DIR__ . '/../view/template/page/add.phtml',
            'admin/page/edit' => __DIR__ . '/../view/template/page/edit.phtml',
            'admin/blogcomments/index' => __DIR__ . '/../view/template/blogcomments/index.phtml',
            'admin/blogcategory/index' => __DIR__ . '/../view/template/blogcategory/index.phtml',
            'admin/blogcategory/add' => __DIR__ . '/../view/template/blogcategory/add.phtml',
            'admin/blogcategory/edit' => __DIR__ . '/../view/template/blogcategory/edit.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'navigation' => array(
        'admin' => array(
            array(
                'label' => 'Page',
                'route' => 'admin-page',
            ),
            array(
                'label' => 'Blog',
                'route' => 'admin-blog',
            ),
            array(
                'label' => 'Blog Category',
                'route' => 'admin-blog-cat',
            ),
            array(
                'label' => 'User',
                'route' => 'admin-user',
            ),
            array(
                'label' => 'My Account',
                'route' => 'admin',
                'action' => 'index',
            ),
            array(
                'label' => 'Logout',
                'route' => 'admin',
                'action' => 'logout',
            ),
        ),
        'login' => array(),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);

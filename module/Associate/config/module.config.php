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
            'associate' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/associate',
                    'defaults' => array(
                        'controller' => 'Associate\Controller\Index',
                        'action'     => 'index',
                        'layout'     => 'layout/two-column-left',
                    ),
                ),
            ),
            'company' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/associate/employer/',
                    'defaults' => array(
                        'controller' => 'associate\Controller\Employer',
                        'action'     => 'index',
                        'layout'     => 'layout/two-column-left',
                    ),
                ),
            ),
            'employee' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/associate/employee/',
                    'defaults' => array(
                        'controller' => 'associate\Controller\Employee',
                        'action'     => 'index',
                        'layout'     => 'layout/two-column-left',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'associate' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/associate[/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Associate\Controller\Index',
                        'action'     => 'index',
                        'layout'     => 'layout/two-column-left',
                    ),
                ),
            ),
            'company' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/associate/employer[/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Associate\Controller\Employer',
                        'action'     => 'index',
                        'layout'     => 'layout/two-column-left',
                    ),
                ),
            ),
            'employee' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/associate/employee[/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Associate\Controller\Employee',
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
            'Associate\Controller\Index' => 'Associate\Controller\IndexController',
            'Associate\Controller\Employer' => 'Associate\Controller\EmployerController',
            'Associate\Controller\Employee' => 'Associate\Controller\EmployeeController'
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
            'associate/index/index' => __DIR__ . '/../view/template/index/index.phtml',
            'associate/index/register' => __DIR__ . '/../view/template/index/register.phtml',
            'associate/index/login' => __DIR__ . '/../view/template/index/login.phtml',
            'associate/employer/index' => __DIR__ . '/../view/template/employer/dashboard.phtml',
            'associate/employer/view' => __DIR__ . '/../view/template/employer/view.phtml',
            'associate/employee/index' => __DIR__ . '/../view/template/employee/dashboard.phtml',
            'associate/index/forgetpass' => __DIR__ . '/../view/template/index/forgetpass.phtml',
            'associate/index/registercompany' => __DIR__ . '/../view/template/index/companyregister.phtml',
            'associate/index/registerjobseeker' => __DIR__ . '/../view/template/index/jobseekerregister.phtml',
            'associate/index/forgetpass' => __DIR__ . '/../view/template/index/forgetpass.phtml',
            'associate/sidebar/employer' => __DIR__ . '/../view/sidebar/employer.phtml',
            'associate/sidebar/employee' => __DIR__ . '/../view/sidebar/employee.phtml',
            'associate/employer/edit' => __DIR__ . '/../view/template/employer/edit.phtml',
            'associate/employee/edit' => __DIR__ . '/../view/template/employee/edit.phtml',
            'associate/employer/changepass' => __DIR__ . '/../view/template/employer/changepass.phtml',
            'associate/employee/changepass' => __DIR__ . '/../view/template/employee/changepass.phtml',
            'associate/employer/changelogo' => __DIR__ . '/../view/template/employer/changelogo.phtml',
            'associate/employee/changelogo' => __DIR__ . '/../view/template/employee/changepic.phtml',
            'associate/employer/employee' => __DIR__ . '/../view/template/employer/employee.phtml',
            'associate/employer/addemployee' => __DIR__ . '/../view/template/employer/employeeadd.phtml',
            'associate/employee/payslips' => __DIR__ . '/../view/template/employee/payslip.phtml',
            'associate/employee/payslipsdownload' => __DIR__ . '/../view/template/employee/payslipdownloads.phtml',
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

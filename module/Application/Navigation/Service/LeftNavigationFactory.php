<?php
namespace Application\Navigation\Service;
 
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
 
/**
* Factory for the frontend navigation
*
* @package    Application
* @subpackage Navigation\Service
*/
class LeftNavigationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $navigation =  new LeftNavigation();
        return $navigation->createService($serviceLocator);
    }
}
?>
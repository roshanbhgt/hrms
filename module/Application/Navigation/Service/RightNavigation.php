<?php
namespace Application\Navigation\Service;
use Zend\Navigation\Service\DefaultNavigationFactory;
/**
* Factory for the Admin admin navigation
*
* @package    Application
* @subpackage Navigation\Service
*/
class RightNavigation extends DefaultNavigationFactory
{
/**
* @{inheritdoc}
*/
protected function getName()
{
return 'right';
}
}
?>
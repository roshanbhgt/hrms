<?php
namespace Application\Navigation\Service;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Service\DefaultNavigationFactory;
/**
* Factory for the Admin admin navigation
*
* @package    Application
* @subpackage Navigation\Service
*/
class LeftNavigation extends DefaultNavigationFactory
{
    protected $menuTable;
    /**
    * @{inheritdoc}
    */
    protected function getName()
    {
        return 'left';
    }
    
    protected function getPages(ServiceLocatorInterface $sm)
    {
        if (null === $this->pages) {
            //FETCH data from table menu :
            if (!$this->menuTable) {
                // $sm = $this->getServiceLocator();
                $this->menuTable = $sm->get('Application\Model\MenuTable');
            }
            $fetchMenu = $this->menuTable->fetchAll();
            
            
            foreach($fetchMenu as $key=>$row)
            {
                $route = explode('/', $row->route);
                if(isset($route[1])){
                    $configuration['navigation'][$this->getName()][$row->title] = array(
                        'label' => $row->label,
                        'route' => $route[0],
                        'params' => array(
                            'action' => 'index',
                            'url' => $route[1],
                        ),
                    );
                }else{
                    $configuration['navigation'][$this->getName()][$row->title] = array(
                        'label' => $row->label,
                        'route' => $row->route
                    );
                }
            }
             
            if (!isset($configuration['navigation'])) {
                throw new Exception\InvalidArgumentException('Could not find navigation configuration key');
            }
            if (!isset($configuration['navigation'][$this->getName()])) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Failed to find a navigation container by the name "%s"',
                    $this->getName()
                ));
            }
 
            $application = $sm->get('Application');
            $routeMatch  = $application->getMvcEvent()->getRouteMatch();
            $router      = $application->getMvcEvent()->getRouter();
            $pages       = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);
            $this->pages = $this->injectComponents($pages, $routeMatch, $router);
        }
        return $this->pages;
    }
}
?>
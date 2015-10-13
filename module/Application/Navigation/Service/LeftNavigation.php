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
    
    /* protected function getPages(ServiceLocatorInterface $sm)
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
                
				$configuration['navigation'][$this->getName()][$row->title] = array(
					'label' => $row->label,
					'route' => $route[0],
				);
				if(isset($route[1])){
					$configuration['navigation'][$this->getName()][$row->title]['params'] = array(
                            'action' => 'index',
                            'url' => $route[1],
                        );
                }
				if(isset($route[2])){
                    $configuration['navigation'][$this->getName()][$row->title]['fragment'] = str_replace('#','',$route[2]);
                }		
				$configuration['navigation'][$this->getName()]['pages'] = array(
					'label' => 'Home',
					'route' => 'home',
				);
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
    } */
	
    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        if (null === $this->pages) {
            //FETCH data from table menu :
            if (!$this->menuTable) {
                // $sm = $this->getServiceLocator();
                $this->menuTable = $serviceLocator->get('Application\Model\MenuTable');
            }
            $fetchMenu = $this->menuTable->fetchAll();
            $configuration['navigation'][$this->getName()] = array();
            if($fetchMenu){ 
                foreach($fetchMenu as $key=>$row)
                {
                    if($row->parent_id === null){
                        $pages = array();
                        $params = array();
                        $fragment = '';
                        $fetchSubMenu = $this->menuTable->fetchAllSubmenu($row->id);
                        if($fetchSubMenu){
                            $page = array();		
                            foreach($fetchSubMenu as $value)
                            {
                                $route = explode('/', $value->route);
                                $page[$value->title]['label'] =$value->label;
                                $page[$value->title]['route'] = $route[0];
                                if(isset($route[1])){
                                    $page[$value->title]['params'] = array(
                                                    'action' => 'index',
                                                    'url' => $route[1],
                                            );
                                }
                                if(isset($route[2])){
                                    $page[$value->title]['fragment'] = str_replace('#','',$route[2]);
                                }

                            }
                            $pages = $page;
                        }
                        $route = explode('/', $row->route);
                        if(isset($route[1])){
                                $params = array(
                                                'action' => 'index',
                                                'url' => $route[1],
                                        );
                        }
                        if(isset($route[2])){
                            $fragment = str_replace('#','',$route[2]);
                        }
                        $configuration['navigation'][$this->getName()][$row->title] = array(
                                'label' => $row->label,
                                'route' => $route[0],
                                'params' => $params,
                                'pages' =>  $pages,
                                'fragment' => $fragment
                        );
                    }
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
            $application = $serviceLocator->get('Application');
            $routeMatch  = $application->getMvcEvent()->getRouteMatch();
            $router      = $application->getMvcEvent()->getRouter();
            $pages       = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);
            $this->pages = $this->injectComponents($pages, $routeMatch, $router);
        }
        return $this->pages;
    }

}
?>
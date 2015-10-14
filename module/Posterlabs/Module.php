<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Posterlabs;

use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\EventManager\EventInterface;
use Zend\Config\Reader\Ini;

use Zend\View\Model\ViewModel;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Posterlabs\Model\Dao\PosterlabsDao;
use Posterlabs\Model\Entity\Posterlabs;



class Module implements BootstrapListenerInterface, ConfigProviderInterface, AutoloaderProviderInterface, ServiceProviderInterface
{
    
 
    
   
    
    public function onBootstrap(EventInterface $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    
        $this->initConfig($e);
    
    }
    
    protected function initConfig(MvcEvent $e){
        $application = $e->getApplication();
        $services = $application->getServiceManager();
    
        $services->setFactory('ConfigPosterlabs', function($services){
            $reader = new Ini();
            $data = $reader->fromFile(__DIR__ . '/config/config.ini');
            return $data;
        });
    
    }
    
    
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    
    public function getControllerConfig(){
        return array(
            'factories'=>array(
                'Posterlabs\Controller\Index'=>function($sm){
                    
                    $locator = $sm->getServiceLocator();
                    $config = $locator->get('ConfigPosterlabs');
                    $controller = new \Admin\Controller\IndexController($config);
                    
                    return $controller;
                },
                
                
                'Posterlabs\Model\PosterlabsDao' => function($sm) {
                $tableGateway = $sm->get('PosterlabsTableGateway');
                $dao = new PosterlabsDao($tableGateway);
                return $dao;
                },
                'PosterlabsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Posterlabs());
                    return new TableGateway('posterlabs', $dbAdapter, null, $resultSetPrototype);
                },
                
            )
        );
    }
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__. '/src/' . str_replace('\\', '/', __NAMESPACE__)
                )
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Posterlabs\Model\PosterlabsDao' => function($sm) {
                    $tableGateway = $sm->get('PosterlabsTableGateway');
                    $dao = new PosterlabsDao($tableGateway);
                    return $dao;
                },
                'PosterlabsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Posterlabs());
                    return new TableGateway('posterlabs', $dbAdapter, null, $resultSetPrototype);
                },
                'Posterlabs\Model\Poster' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                return new \Posterlabs\Model\Poster($dbAdapter);
                }
            )
            
        );
    }

   
}

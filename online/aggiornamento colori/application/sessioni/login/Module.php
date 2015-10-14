<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Relatori;

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

use Relatori\Model\Dao\RelatoriDao;
use Relatori\Model\Entity\Relatori;
use Relatori\Controller\Listener\LanguageListener;



class Module implements BootstrapListenerInterface, ConfigProviderInterface, AutoloaderProviderInterface, ServiceProviderInterface
{
    
 
    
   
    
    public function onBootstrap(EventInterface $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $headers = $e->getResponse()->getHeaders();
        $headers->addHeaderLine('Access-Control-Allow-Origin: *');

        $headers->addHeaderLine('Access-Control-Allow-Methods: PUT, GET, POST, PATCH, DELETE, OPTIONS');
        $headers->addHeaderLine('Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept');
        $this->initConfig($e);
        $e->getApplication()->getEventManager()->attach('render', array($this, 'registerJsonStrategy'), 100);
    
    }
    
    protected function initConfig(MvcEvent $e){
        $application = $e->getApplication();
        $services = $application->getServiceManager();
    
        $services->setFactory('ConfigRelatori', function($services){
            $reader = new Ini();
            $data = $reader->fromFile(__DIR__ . '/config/config.ini');
            return $data;
        });
    
    }
    
    public function registerJsonStrategy($e) {
        $application = $e->getTarget();
    
        $matches = $e->getRouteMatch();
        $controller = $matches->getParam('controller');
    
        switch ($controller) {
            case "Relatori\Controller\EjemploServidorRestful":
                $sm = $application->getServiceManager();
                $view = $sm->get('Zend\View\View');
                $jsonStrategy = $sm->get('ViewJsonStrategy');
    
                // Agregamos la estrategia la listener, con prioridad alta
                $view->getEventManager()->attach($jsonStrategy, 100);
    
                //Otra forma:
                //$strategy = $locator->get('ViewJsonStrategy');
                //$view = $locator->get('ViewManager')->getView();
                //$strategy->attach($view->getEventManager());
                break;
        }
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    
    public function getControllerConfig(){
        return array(
            'factories'=>array(
                'Relatori\Controller\Index'=>function($sm){
                    
                    $locator = $sm->getServiceLocator();
                    $config = $locator->get('ConfigRelatori');
                    $controller = new \Admin\Controller\IndexController($config);
                    
                    return $controller;
                },
                'Admin\Controller\Relatori'=>function($sm){
                $locator = $sm->getServiceLocator();
                $config = $locator->get('ConfigIniAdmin');
                $controller = new \Admin\Controller\RelatoriController($config);
                return $controller;
                },
                
                'Relatori\Model\RelatoriDao' => function($sm) {
                $tableGateway = $sm->get('RelatoriTableGateway');
                $dao = new RelatoriDao($tableGateway);
                return $dao;
                },
                'RelatoriTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Relatori());
                    return new TableGateway('relatori', $dbAdapter, null, $resultSetPrototype);
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
                'Relatori\Model\Relato' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                return new \Relatori\Model\Relato($dbAdapter);
                },
                'Relatori\Model\RelatoriDao' => function($sm) {
                    $tableGateway = $sm->get('RelatoriTableGateway');
                    $dao = new RelatoriDao($tableGateway);
                    return $dao;
                },
                'RelatoriTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Relatori());
                    return new TableGateway('relatori', $dbAdapter, null, $resultSetPrototype);
                },
                
            )
            
        );
    }

   
}

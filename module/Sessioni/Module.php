<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Sessioni;

use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\EventManager\EventInterface;

use Zend\Config\Reader\Ini;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Sessioni\Model\Dao\SessioniDao;
use Sessioni\Model\Entity\Sessioni;



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
    
        $services->setFactory('ConfigSession', function($services){
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
                
                
                'Sessioni\Model\SessioniDao' => function($sm) {
                $tableGateway = $sm->get('SessioniTableGateway');
                $dao = new SessioniDao($tableGateway);
                return $dao;
                },
                'SessioniTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Sessioni());
                    return new TableGateway('sessioni', $dbAdapter, null, $resultSetPrototype);
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
                'Sessioni\Model\SessioniDao' => function($sm) {
                    $tableGateway = $sm->get('SessioniTableGateway');
                    $dao = new SessioniDao($tableGateway);
                    return $dao;
                },
                'SessioniTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Sessioni());
                    return new TableGateway('sessioni', $dbAdapter, null, $resultSetPrototype);
                },
                
            )
            
        );
    }

   
}

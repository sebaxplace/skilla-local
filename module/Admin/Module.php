<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;

use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\EventManager\EventInterface;
use Zend\Config\Reader\Ini;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\View\Helper\ViewModel;
use Admin\View\Helper\Indirizzi;



class Module implements BootstrapListenerInterface, ConfigProviderInterface, AutoloaderProviderInterface, ServiceProviderInterface, ControllerProviderInterface
{
    
    public function init(ModuleManager $moduleManager) {
        $events = $moduleManager->getEventManager();
        $sharedEvents = $events->getSharedManager();
        $sharedEvents->attach(array(__NAMESPACE__, 'Admin', 'Application'), 'dispatch', array($this, 'initAuth'), 100);
       
    }
    
    
    public function initAuth(MvcEvent $e) {
        $application = $e->getApplication();
        $matches = $e->getRouteMatch();
        $controller = $matches->getParam('controller');
        $action = $matches->getParam('action');

        /*if (0 !== strpos($controller, __NAMESPACE__, 0)) {
            // Validamos cuando el controlador sea del modulo
            return;
        }
        */

        if ($controller === "Admin\Controller\Login" && in_array($action, array('index', 'autenticar'))) {
            // Validamos cuando el controlador sea LoginController
            // exepto las acciones index y autenticar.
            return;
        }

        $sm = $application->getServiceManager();

        $auth = $sm->get('Admin\Model\Login');

        if(!$auth->isLoggedIn()){
            $controller=$e->getTarget();
            return $controller->redirect()->toRoute('admin', array('controller'=>'login', 'action'=>'index'));
        }
    }
    
   
    
    public function onBootstrap(EventInterface $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $app = $e->getParam('application');
       // $app->getEventManager()->attach('dispatch', array($this, 'initAcl'), 100);
        $this->initConfig($e);
        $this->initLayout($e);
        $app->getEventManager()->attach('dispatch', array($this, 'setLayout'), 100);
    
    }
    
    
    
    
    public function initAcl(MvcEvent $e){
        $acl = new Acl();
        $acl->addRole(new Role('invitado'))
            ->addRole(new Role('miembro'), 'invitado')
            ->addRole(new Role('admin'), 'miembro');
        
        
        
        
        
        $acl
        //->addResource(new Resource('catalogo:index'))
            ->addResource(new Resource('application:index'))
            ->addResource(new Resource('application:tabular'))
            ->addResource(new Resource('usuarios:index'))
            ->addResource(new Resource('admin:index'))
            ->addResource(new Resource('admin:login'))
            ->addResource(new Resource('admin:error'))
            ->addResource(new Resource('admin:usuarios'))
            ->allow('invitado', 'admin:error')
            ->allow('invitado', 'application:index')
            ->allow('invitado', 'application:tabular')
            ->allow('invitado', 'usuarios:index')
            ->allow('invitado', 'admin:index')
            ->allow('invitado', 'admin:login', array('index', 'autenticar'))
            //->allow('invitado', 'catalogo:index', array('index', 'listar', ))
            ->allow('miembro', 'admin:login', array('logout'))
            //->allow('miembro', 'catalogo:index', array('index', 'listar', 'con-precio'))
            ->deny('miembro', 'admin:index')
            ->allow('admin');
        
        $application = $e->getApplication();
        $services = $application->getServiceManager();
        $services->setService('AclService', $acl);
        
        $matches = $e->getRouteMatch();
        
        $controllerClass= $matches->getParam('controller');
        $controllerArray = explode("\\", $controllerClass);
        
        $module = strtolower($controllerArray[0]);
        $controller = strtolower($controllerArray[2]);
        
        $action = $matches->getParam('action');
        
        $resourceName = $module . ':'. $controller;
        
        $viewModel = $e->getViewModel();
        $viewModel->acl = $acl;
       
        
        if(!$acl->isAllowed($this->getRole($services), $resourceName, $action)){
            //redirigir al controlador de errores
            $matches->setParam('controller', 'Admin\Controller\Error');
            $matches->setParam('action', 'denied');
        }
        
    }
   
    private function getRole($sm)
    {
        $auth = $sm->get('Admin\Model\Login');
        $role = "invitado";
        if ($auth->isLoggedIn()) {
            if (! empty($auth->getIdentity()->role)) {
               
                if($auth->getIdentity()->role == 1){$role = "miembro";}
                if($auth->getIdentity()->role == 2){$role = "admin";}
                
            }
        }
        return $role;
    }

    
    public function getViewHelperConfig() {
        return array(
            'factories' => array(
                'HeaderValue' => function($sm) {
                    $locator = $sm->getServiceLocator();
                    $login = $locator->get('Admin\Model\Login');
                    return new View\Helper\HeaderValue($login); 
                    },
                    
                 'Indirizzi' => function($sm) {
                    $locator = $sm->getServiceLocator(); 
                    return new View\Helper\Indirizzi($locator->get('Request')); 
                    } 
            ), );
    }
    
    
    protected function initConfig(MvcEvent $e){
        $application = $e->getApplication();
        $services = $application->getServiceManager();
    
        $services->setFactory('ConfigIniAdmin', function($services){
            $reader = new Ini();
            $data = $reader->fromFile(__DIR__ . '/config/config.ini');
            return $data;
        });
    
    }
    
    protected function initLayout(MvcEvent $e) {
        $layout = $e->getViewModel();
        $application = $e->getApplication();
        $sm = $application->getServiceManager();
        $auth = $sm->get('Admin\Model\Login');
        $layout->user = $auth->getIdentity();
        $layout->isLoggedIn = $auth->isLoggedIn();
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                
                'Admin\Model\Login' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return new \Admin\Model\Login($dbAdapter);
                },
                
            )
    
        );
    }
    public function setLayout(MvcEvent $e)
    {
        $matches = $e->getRouteMatch();
        $controller = $matches->getParam('controller');
        if (0 !== strpos($controller, __NAMESPACE__, 0)) {
            // not a controller from this module
            return;
        }
        // Set the layout template
        
        $viewModel = $e->getViewModel();
        $viewModel->setTemplate('content/layout');
        
    }
    public function getControllerConfig(){
        return array(
            'factories'=>array(
                'Admin\Controller\Interattivo'=>function($sm){
                    $locator = $sm->getServiceLocator();
                    $config = $locator->get('ConfigInterattivo');
                    $controller = new \Admin\Controller\InterattivoController($config);
                    return $controller;
                },
                'Admin\Controller\Contenuti'=>function($sm){
                    $locator = $sm->getServiceLocator();
                    $config = $locator->get('ConfigContenuti');
                    $controller = new \Admin\Controller\ContenutiController($config);
                    return $controller;
                },
                'Admin\Controller\Utenti'=>function($sm){
                    $locator = $sm->getServiceLocator();
                    $config = $locator->get('ConfigIniAdmin');
                    $controller = new \Admin\Controller\UtentiController($config);
                    return $controller;
                },
                
                'Admin\Controller\Posterlabs'=>function($sm){
                    $locator = $sm->getServiceLocator();
                    $config = $locator->get('ConfigPosterlabs');
                    $controller = new \Admin\Controller\PosterlabsController($config);
                    return $controller;
                },
                'Admin\Controller\Login' => function ($sm) {
                    $controller = new \Admin\Controller\LoginController();
                    $locator = $sm->getServiceLocator();
                    $controller->setLogin($locator->get('Admin\Model\Login'));
                    return $controller;
                },
                'Admin\Controller\Index' => function ($sm) {
                    $controllers = new \Admin\Controller\IndexController();
                    $locator = $sm->getServiceLocator();
                    $controllers->setLogin($locator->get('Admin\Model\Login'));
                    return $controllers;
                }
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
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

  
}

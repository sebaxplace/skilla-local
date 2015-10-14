<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Relatori\Controller\EjemploServidorRestful' => 'Relatori\Controller\EjemploServidorRestfulController',
            'Relatori\Controller\EjemploClienteRestful' => 'Relatori\Controller\EjemploClienteRestfulController',
            'Relatori\Controller\Index' => 'Relatori\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'relatori' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/relatori',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Relatori\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller][/:action]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                
                            ),
                        ),
                    ),
                ),
            ),
            'restful' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/relatori/rest[/:controller][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Relatori\Controller',
                        'controller' => 'EjemploServidorRestful',
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_map' => array(
            
            'relatori/estudiante/index' => __DIR__ . '/../view/relatori/estudiante/index.phtml',
            'relatori/estudiante/registrar' => __DIR__ . '/../view/relatori/estudiante/registrar.phtml',
            'Relatori\Controller\EjemploServidorRestful' => 'Relatori\Controller\EjemploServidorRestfulController',
            'Relatori\Controller\EjemploClienteRestful' => 'Relatori\Controller\EjemploClienteRestfulController',
            
        ),
        'template_path_stack' => array(
           'Relatori'=> __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewFeedStrategy',
        ),
    ),
);

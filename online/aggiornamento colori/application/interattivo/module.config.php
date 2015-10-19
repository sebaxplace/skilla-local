<?php
return array(
    'controllers' => array(
        'invokables' => array(
           'Interattivo\Controller\Index' => 'Interattivo\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'interattivo' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/interattivo[/:controller][/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Interattivo\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_map' => array(
            
            'interattivo/estudiante/index' => __DIR__ . '/../view/interattivo/estudiante/index.phtml',
            'interattivo/estudiante/registrar' => __DIR__ . '/../view/interattivo/estudiante/registrar.phtml',
            
        ),
        'template_path_stack' => array(
           'Interattivo'=> __DIR__ . '/../view',
        ),
    ),
);

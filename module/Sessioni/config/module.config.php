<?php
return array(
    'controllers' => array(
        'invokables' => array(
            
        ),
    ),
    'router' => array(
        'routes' => array(
            'sessioni' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/sessioni[/:controller][/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Sessioni\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_map' => array(
            
           'sessioni/estudiante/registrar' => __DIR__ . '/../view/sessioni/estudiante/registrar.phtml',
            
        ),
        'template_path_stack' => array(
           'Sessioni'=> __DIR__ . '/../view',
        ),
    ),
);

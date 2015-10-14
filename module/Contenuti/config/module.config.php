<?php
return array(
    'controllers' => array(
        'invokables' => array(
            //'Relatori\Controller\Estudiante' => 'Relatori\Controller\EstudianteController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'usuarios' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/contenuti[/:controller][/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Contenuti\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_map' => array(
            
            'posterlabs/estudiante/index' => __DIR__ . '/../view/posterlabs/estudiante/index.phtml',
            'posterlabs/estudiante/registrar' => __DIR__ . '/../view/posterlabs/estudiante/registrar.phtml',
            
        ),
        'template_path_stack' => array(
           'Relatori'=> __DIR__ . '/../view',
        ),
    ),
);

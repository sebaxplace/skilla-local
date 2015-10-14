<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Utenti\Controller\Estudiante' => 'Utenti\Controller\EstudianteController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'usuarios' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/utenti[/:controller][/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Utenti\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_map' => array(
            
            'utenti/estudiante/index' => __DIR__ . '/../view/utenti/estudiante/index.phtml',
            'utenti/estudiante/registrar' => __DIR__ . '/../view/utenti/estudiante/registrar.phtml',
            
        ),
        'template_path_stack' => array(
           'Usuarios'=> __DIR__ . '/../view',
        ),
    ),
);

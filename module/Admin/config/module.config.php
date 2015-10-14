<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Error' => 'Admin\Controller\ErrorController',
            
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin[/:controller][/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'admin/error/denied' => __DIR__ . '/../view/admin/error/denied.phtml',
            'admin/login/login' => __DIR__ . '/../view/admin/login/login.phtml',
            'content/layout'           => __DIR__ . '/../view/content/layout.phtml',
            'content/header'           => __DIR__ . '/../view/content/header.phtml',
            'content/footer'           => __DIR__ . '/../view/content/footer.phtml',
            'content/sidebar'           => __DIR__ . '/../view/content/sidebar.phtml',
        ),
        'template_path_stack' => array(
           'Admin'=> __DIR__ . '/../view',
        ),
        'strategies'=>array(
            'ViewJsonStrategy',
        ),
    ),
);

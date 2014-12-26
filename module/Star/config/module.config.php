<?php

/**
 * 总配置
 */
return array(
    /** 控制器配置，如果想要控制器生效的话，须在这里声明 */
    'controllers' => array(
        'invokables' => array(
            'Star\Controller\Index' => 'Star\Controller\IndexController',
            'Star\Controller\Home' => 'Star\Controller\HomeController',
        ),
    ),
    /** 路由 */
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Star\Controller',
                        'controller' => 'Star\Controller\Home',
                        'action' => 'index',
                    ),
                ),
            ),
            'star' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/star[/][:action][/:id[/page/:page]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'page' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Star\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    /** 视图管理器 */
    'view_manager' => array(
        'template_path_stack' => array(
            'star' => __DIR__ . '/../view',
        ),
    ),
    /** 服务器管理器 */
    'service_manager' => array(
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
    ),
    /** 译码器或者翻译器 */
    'translator' => array(),
    /** 页面导航 */
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Home',
                'route' => 'home'
            ),
            array(
                'label' => 'Star',
                'route' => 'star',
                'pages' => array(
                    array(
                        'label' => 'Add',
                        'route' => 'star',
                        'action' => 'add'
                    ),
                    array(
                        'label' => 'Edit',
                        'route' => 'star',
                        'action' => 'edit'
                    ),
                    array(
                        'label' => 'Delete',
                        'route' => 'star',
                        'action' => 'delete'
                    ),
                ),
            ),
        ),
    ),
);

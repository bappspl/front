<?php

return array(
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'controllers' => array(
        'invokables' => array(
            'Page\Controller\Page' => 'Page\Controller\PageController'
        ),
    ),
    'view_manager' => array(
        'doctype'                  => 'HTML5',
        'template_map' => array(
            'layout/home' => __DIR__ . '/../view/layout/home.phtml',
            'partial/layout/header' => __DIR__ . '/../view/partial/layout/header.phtml',
            'partial/layout/footer' => __DIR__ . '/../view/partial/layout/footer.phtml'
        ),
        'template_path_stack' => array(
            'page_home_site' => __DIR__ . '/../view'
        ),
        'display_exceptions' => true,
    ),
    'view_helpers' => array(
        'invokables'=> array(
            'menuHelper' => 'CmsIr\Menu\View\Helper\MenuHelper',
        ),
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    ),

);

<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Product\Controller\Product' => 'Product\Controller\ProductController'
        ),

        'factories' => array(
            'Product\Console\ProductCommand' => 'Product\Console\ProductCommandFactory',
        )
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages'  => __DIR__ . '/../view/partial/flashmessages.phtml',
            'partial/delete-modal'  => __DIR__ . '/../view/partial/delete-modal.phtml',
            'partial/delete-massive-product-modal'  => __DIR__ . '/../view/partial/delete-massive-product-modal.phtml',
            'partial/status-massive-product-modal'  => __DIR__ . '/../view/partial/status-massive-product-modal.phtml',
            'partial/language-product'  => __DIR__ . '/../view/partial/language.phtml',
        ),
        'template_path_stack' => array(
            'product' => __DIR__ . '/../view'
        ),
        'display_exceptions' => true,
    ),
    'service_manager' => array(

    ),
    'strategies' => array(
        'ViewJsonStrategy',
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => include __DIR__ . '/console.config.php',
        ),
    ),
);
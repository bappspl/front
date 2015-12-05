<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Performance\Controller\Performance' => 'Performance\Controller\PerformanceController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages-performance'  => __DIR__ . '/../view/partial/flashmessages-performance.phtml',
            'partial/delete-performance-modal'  => __DIR__ . '/../view/partial/delete-performance-modal.phtml',
            'partial/delete-massive-performance-modal'  => __DIR__ . '/../view/partial/delete-massive-performance-modal.phtml',
            'partial/language-performance'  => __DIR__ . '/../view/partial/language.phtml',
        ),
        'template_path_stack' => array(
            'performance' => __DIR__ . '/../view'
        ),
        'display_exceptions' => true,
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Performance\Service\PerformanceService' => 'Performance\Service\Factory\PerformanceService',
        ),
    ),
);
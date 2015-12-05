<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Disc\Controller\Disc' => 'Disc\Controller\DiscController',
            'Disc\Controller\Record' => 'Disc\Controller\RecordController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages-disc'  => __DIR__ . '/../view/partial/flashmessages-disc.phtml',
            'partial/delete-disc-modal'  => __DIR__ . '/../view/partial/delete-disc-modal.phtml',
            'partial/delete-massive-disc-modal'  => __DIR__ . '/../view/partial/delete-massive-disc-modal.phtml',
            'partial/delete-record-modal'  => __DIR__ . '/../view/partial/delete-record-modal.phtml',
            'partial/delete-massive-record-modal'  => __DIR__ . '/../view/partial/delete-massive-record-modal.phtml',
        ),
        'template_path_stack' => array(
            'disc' => __DIR__ . '/../view'
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
            'Disc\Service\DiscService' => 'Disc\Service\Factory\DiscService',
        ),
    ),
);
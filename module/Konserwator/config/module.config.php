<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Konserwator\Controller\Konserwator' => 'Konserwator\Controller\KonserwatorController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages-konserwator'  => __DIR__ . '/../view/partial/flashmessages-konserwator.phtml',
            'partial/delete-konserwator-modal'  => __DIR__ . '/../view/partial/delete-konserwator-modal.phtml',
            'partial/delete-massive-konserwator-modal'  => __DIR__ . '/../view/partial/delete-massive-konserwator-modal.phtml',
        ),
        'template_path_stack' => array(
            'konserwator' => __DIR__ . '/../view'
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
            'Konserwator\Service\KonserwatorService' => 'Konserwator\Service\Factory\KonserwatorService',
        ),
    ),
);
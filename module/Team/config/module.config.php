<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Team\Controller\Team' => 'Team\Controller\TeamController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages-team'  => __DIR__ . '/../view/partial/flashmessages-team.phtml',
            'partial/delete-team-modal'  => __DIR__ . '/../view/partial/delete-team-modal.phtml',
            'partial/delete-massive-team-modal'  => __DIR__ . '/../view/partial/delete-massive-team-modal.phtml',
        ),
        'template_path_stack' => array(
            'team' => __DIR__ . '/../view'
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
            'Team\Service\TeamService' => 'Team\Service\Factory\TeamService',
        ),
    ),
);
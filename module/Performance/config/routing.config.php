<?php
return array(
    'performance-main' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/performance',
            'defaults' => array(
                'module' => 'CmsIr\Performance',
                'controller' => 'CmsIr\Performance\Controller\Performance',
                'action'     => 'list',
            ),
        )
    ),
    'performance' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/performance',
            'defaults' => array(
                'module' => 'Performance',
                'controller' => 'Performance\Controller\Performance',
                'action'     => 'list',
            ),
            'constraints' => array(
                'performance' => '[a-zA-Z0-9_-]+'
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'Performance',
                        'controller' => 'Performance\Controller\Performance',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:performance_id',
                    'defaults' => array(
                        'module' => 'Performance',
                        'controller' => 'Performance\Controller\Performance',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'performance_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:performance_id',
                    'defaults' => array(
                        'module' => 'Performance',
                        'controller' => 'Performance\Controller\Performance',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'performance_id' => '[0-9]+'
                    ),
                ),
            ),
            'change-status' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/change-status/:performance_id',
                    'defaults' => array(
                        'module' => 'Performance',
                        'controller' => 'Performance\Controller\Performance',
                        'action'     => 'changeStatus',
                    ),
                    'constraints' => array(
                        'performance_id' =>  '[0-9]+'
                    ),
                ),
            ),
        ),
    ),
);
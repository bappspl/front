<?php
return array(
    'konserwator-main' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/konserwator',
            'defaults' => array(
                'module' => 'CmsIr\Konserwator',
                'controller' => 'CmsIr\Konserwator\Controller\Konserwator',
                'action'     => 'list',
            ),
        )
    ),
    'konserwator' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/konserwator',
            'defaults' => array(
                'module' => 'Konserwator',
                'controller' => 'Konserwator\Controller\Konserwator',
                'action'     => 'list',
            ),
            'constraints' => array(
                'konserwator' => '[a-zA-Z0-9_-]+'
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'Konserwator',
                        'controller' => 'Konserwator\Controller\Konserwator',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:konserwator_id',
                    'defaults' => array(
                        'module' => 'Konserwator',
                        'controller' => 'Konserwator\Controller\Konserwator',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'konserwator_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:konserwator_id',
                    'defaults' => array(
                        'module' => 'Konserwator',
                        'controller' => 'Konserwator\Controller\Konserwator',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'konserwator_id' => '[0-9]+'
                    ),
                ),
            ),
            'change-status' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/change-status/:konserwator_id',
                    'defaults' => array(
                        'module' => 'Konserwator',
                        'controller' => 'Konserwator\Controller\Konserwator',
                        'action'     => 'changeStatus',
                    ),
                    'constraints' => array(
                        'konserwator_id' =>  '[0-9]+'
                    ),
                ),
            ),
        ),
    ),
);
<?php
return array(
    'team-main' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/team',
            'defaults' => array(
                'module' => 'CmsIr\Team',
                'controller' => 'CmsIr\Team\Controller\Team',
                'action'     => 'list',
            ),
        )
    ),
    'team' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/team',
            'defaults' => array(
                'module' => 'Team',
                'controller' => 'Team\Controller\Team',
                'action'     => 'list',
            ),
            'constraints' => array(
                'team' => '[a-zA-Z0-9_-]+'
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'Team',
                        'controller' => 'Team\Controller\Team',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:team_id',
                    'defaults' => array(
                        'module' => 'Team',
                        'controller' => 'Team\Controller\Team',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'team_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:team_id',
                    'defaults' => array(
                        'module' => 'Team',
                        'controller' => 'Team\Controller\Team',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'team_id' => '[0-9]+'
                    ),
                ),
            ),
            'change-status' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/change-status/:team_id',
                    'defaults' => array(
                        'module' => 'Team',
                        'controller' => 'Team\Controller\Team',
                        'action'     => 'changeStatus',
                    ),
                    'constraints' => array(
                        'team_id' =>  '[0-9]+'
                    ),
                ),
            ),
            'delete-photo-main' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/delete-photo-main',
                    'defaults' => array(
                        'module' => 'Team',
                        'controller' => 'Team\Controller\Team',
                        'action'     => 'deletePhotoMain',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'upload-main' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/upload-main',
                    'defaults' => array(
                        'module' => 'Team',
                        'controller' => 'Team\Controller\Team',
                        'action'     => 'uploadFilesMain',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
        ),
    ),
);
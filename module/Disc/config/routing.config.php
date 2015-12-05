<?php
return array(
    'disc-main' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/disc',
            'defaults' => array(
                'module' => 'CmsIr\Disc',
                'controller' => 'CmsIr\Disc\Controller\Disc',
                'action'     => 'list',
            ),
        )
    ),
    'disc' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/disc',
            'defaults' => array(
                'module' => 'Disc',
                'controller' => 'Disc\Controller\Disc',
                'action'     => 'list',
            ),
            'constraints' => array(
                'disc' => '[a-zA-Z0-9_-]+'
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'Disc',
                        'controller' => 'Disc\Controller\Disc',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:disc_id',
                    'defaults' => array(
                        'module' => 'Disc',
                        'controller' => 'Disc\Controller\Disc',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'disc_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:disc_id',
                    'defaults' => array(
                        'module' => 'Disc',
                        'controller' => 'Disc\Controller\Disc',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'disc_id' => '[0-9]+'
                    ),
                ),
            ),
            'change-status' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/change-status/:disc_id',
                    'defaults' => array(
                        'module' => 'Disc',
                        'controller' => 'Disc\Controller\Disc',
                        'action'     => 'changeStatus',
                    ),
                    'constraints' => array(
                        'disc_id' =>  '[0-9]+'
                    ),
                ),
            ),
            'delete-photo-main' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/delete-photo-main',
                    'defaults' => array(
                        'module' => 'Disc',
                        'controller' => 'Disc\Controller\Disc',
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
                        'module' => 'Disc',
                        'controller' => 'Disc\Controller\Disc',
                        'action'     => 'uploadFilesMain',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'change-position' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/change-position',
                    'defaults' => array(
                        'module' => 'Disc',
                        'controller' => 'Disc\Controller\Disc',
                        'action'     => 'changePosition',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'records' => array(
                'may_terminate' => true,
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/records/:disc_id',
                    'defaults' => array(
                        'module' => 'Disc',
                        'controller' => 'Disc\Controller\Record',
                        'action'     => 'list',
                    ),
                    'constraints' => array(
                        'disc_id' => '[0-9]+'
                    ),
                ),
                'child_routes' => array(
                    'create' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/create',
                            'defaults' => array(
                                'module' => 'Disc',
                                'controller' => 'Disc\Controller\Record',
                                'action' => 'create',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/edit/:record_id',
                            'defaults' => array(
                                'module' => 'Disc',
                                'controller' => 'Disc\Controller\Record',
                                'action' => 'edit',
                            ),
                            'constraints' => array(
                                'record_id' => '[0-9]+'
                            ),
                        ),
                    ),
                    'delete' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/delete/:record_id',
                            'defaults' => array(
                                'module' => 'Disc',
                                'controller' => 'Disc\Controller\Record',
                                'action' => 'delete',
                            ),
                            'constraints' => array(
                                'record_id' => '[0-9]+'
                            ),
                        ),
                    ),
                    'change-status' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/change-status/:record_id',
                            'defaults' => array(
                                'module' => 'Disc',
                                'controller' => 'Disc\Controller\Record',
                                'action'     => 'changeStatus',
                            ),
                            'constraints' => array(
                                'record_id' =>  '[0-9]+'
                            ),
                        ),
                    ),
                )
            ),
        ),
    ),
);
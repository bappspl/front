<?php
return array(
    'product-main' => array(
        'may_terminate' => true,
        'type' => 'Literal',
        'options' => array(
            'route' => '/cms-ir/product',
            'defaults' => array(
                'module' => 'Product',
                'controller' => 'Product\Controller\Product',
                'action' => 'list',
            ),
        ),
    ),
    'product' => array(
        'may_terminate' => true,
        'type' => 'Literal',
        'options' => array(
            'route' => '/cms-ir/product',
            'defaults' => array(
                'module' => 'Product',
                'controller' => 'Product\Controller\Product',
                'action' => 'list',
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'Product',
                        'controller' => 'Product\Controller\Product',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:product_id',
                    'defaults' => array(
                        'module' => 'Product',
                        'controller' => 'Product\Controller\Product',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'product_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:product_id',
                    'defaults' => array(
                        'module' => 'Product',
                        'controller' => 'Product\Controller\Product',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'product_id' => '[0-9]+'
                    ),
                ),
            ),
            'upload' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/upload',
                    'defaults' => array(
                        'module' => 'Product',
                        'controller' => 'Product\Controller\Product',
                        'action'     => 'uploadFiles',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'delete-photo' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/delete-photo',
                    'defaults' => array(
                        'module' => 'Product',
                        'controller' => 'Product\Controller\Product',
                        'action'     => 'deletePhoto',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'change-status' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/change-status/:product_id',
                    'defaults' => array(
                        'module' => 'Product',
                        'controller' => 'Product\Controller\Product',
                        'action'     => 'changeStatus',
                    ),
                    'constraints' => array(
                        'product_id' =>  '[0-9]+'
                    ),
                ),
            ),
            'parse-csv' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/parse-csv',
                    'defaults' => array(
                        'module' => 'Product',
                        'controller' => 'Product\Controller\Product',
                        'action' => 'parseCsv',
                    ),
                ),
            ),
        ),
    ),
);

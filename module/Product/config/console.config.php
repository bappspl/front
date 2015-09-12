<?php
return array(
    'parse-csv' => array(
        'options' => array(
            'route'    => 'parse-csv',
            'defaults' => array(
                'controller' => 'Product\Console\ProductCommand',
                'action'     => 'parseCsv'
            )
        )
    ),
);
<?php
return array(
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=refactor;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => '',
                    'dbname'   => 'refactor',
                )
            )
        )
    ),
    'service_manager' => array(
     'factories' => array(
         'Zend\Db\Adapter\Adapter'
                 => 'Zend\Db\Adapter\AdapterServiceFactory',
     ),
    ),
    'static_salt' => 'aFGQ475SDsdfsaf2342',
    'app_name' => 'Refactor',
    'logger_mail' => false,
    'logger_show' => true,
    'piwik' => 'http://piwik.web-ir.pl'
);

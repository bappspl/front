<?php
return array(
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=grodzka;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
     'service_manager' => array(
         'factories' => array(
             'Zend\Db\Adapter\Adapter'
                     => 'Zend\Db\Adapter\AdapterServiceFactory',
         ),
     ),
     'static_salt' => 'aFGQ475SDsdfsaf2342',
     'app_name' => 'cms-master',
     'logger_mail' => true,
     'piwik' => 'http://piwik.web-ir.pl'
);

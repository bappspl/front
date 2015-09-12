<?php
return array(
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=tartak;host=localhost',
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
     'app_name' => 'tartak',
     'logger_mail' => false,
     'piwik' => 'http://piwik.web-ir.pl',
     'languages' => array('pl', 'en', 'de')
);

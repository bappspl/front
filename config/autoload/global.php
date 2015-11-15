<?php
return array(
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=demo;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
     'service_manager' => array(
         'factories' => array(
             'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
             'Zend\Cache\Storage\Filesystem' => function($sm){
                 $cache = Zend\Cache\StorageFactory::factory(array(
                     'adapter' => 'filesystem',
                     'plugins' => array(
                         'exception_handler' => array('throw_exceptions' => false),
                         'serializer'
                     )
                 ));

                 $cache->setOptions(array(
                     'cache_dir' => './data/cache'
                 ));

                 return $cache;
             },
         ),
     ),
     'static_salt' => 'aFGQ475SDsdfsaf2342',
     'app_name' => 'cms-demo',
     'logger_mail' => false,
     'piwik' => 'http://piwik.web-ir.pl',
     'languages' => array('pl', 'en', 'de')
);
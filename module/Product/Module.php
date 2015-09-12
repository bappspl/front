<?php
namespace Product;

use Product\Model\Product;
use Product\Model\ProductTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\MvcEvent;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {

    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/Product',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Product\Model\ProductTable' =>  function($sm) {
                        $tableGateway = $sm->get('ProductTableGateway');
                        $table = new ProductTable($tableGateway);
                        return $table;
                    },
                'ProductTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Product());
                        return new TableGateway('product', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
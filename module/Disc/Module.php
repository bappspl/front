<?php
namespace Disc;

use CmsIr\Page\Model\Page;
use CmsIr\Page\Model\PageTable;
use Disc\Model\Record;
use Disc\Model\RecordTable;
use Performance\Model\Performance;
use Performance\Model\PerformanceTable;
use Disc\Model\Disc;
use Disc\Model\DiscTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $viewModel = $e->getViewModel();

        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
            $loggedUser = $auth->getIdentity();
            $viewModel->loggedUser = $loggedUser;
        }
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
                    __NAMESPACE__ => __DIR__ . '/src/Disc',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Disc\Model\DiscTable' =>  function($sm) {
                        $tableGateway = $sm->get('DiscTableGateway');
                        $table = new DiscTable($tableGateway);
                        return $table;
                    },
                'DiscTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Disc());
                        return new TableGateway('disc', $dbAdapter, null, $resultSetPrototype);
                },
                'Record\Model\RecordTable' =>  function($sm) {
                    $tableGateway = $sm->get('RecordTableGateway');
                    $table = new RecordTable($tableGateway);
                    return $table;
                },
                'RecordTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Record());
                    return new TableGateway('record', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
<?php
namespace Team;

use CmsIr\Page\Model\Page;
use CmsIr\Page\Model\PageTable;
use Performance\Model\Performance;
use Performance\Model\PerformanceTable;
use Team\Model\Team;
use Team\Model\TeamTable;
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
                    __NAMESPACE__ => __DIR__ . '/src/Team',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Team\Model\TeamTable' =>  function($sm) {
                        $tableGateway = $sm->get('TeamTableGateway');
                        $table = new TeamTable($tableGateway);
                        return $table;
                    },
                'TeamTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Team());
                        return new TableGateway('team', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
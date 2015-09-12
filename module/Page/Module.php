<?php

namespace Page;

use CmsIr\Page\Model\Page;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $em = $e->getApplication()->getEventManager();
        $em->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'));
    }

    public function onDispatch(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $routeMatch = $e->getRouteMatch();

        //lang
        $lang = $this->getLanguageService($sm)->setLanguage($routeMatch->getParams());

        $langId = $this->getLanguageTable($sm)->getOneBy(array('url_shortcut' => $lang))->getId();

        $menu = $this->getMenuService($sm)->getMenuByMachineName('main-menu');

        $viewModel = $e->getViewModel();
        $viewModel->menu = $menu;
        $viewModel->langId = $langId;

        $session = new Container();
        $session->id = $langId;

        $viewModel = $e->getViewModel();
        $viewModel->langUp = strtoupper($lang);
        $viewModel->lang = $lang;
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
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @return \CmsIr\Menu\Service\MenuService
     */
    public function getMenuService($sm)
    {
        return $sm->get('CmsIr\Menu\Service\MenuService');
    }

    /**
     * @return \CmsIr\System\Service\LanguageService
     */
    public function getLanguageService($sm)
    {
        return $sm->get('CmsIr\System\Service\LanguageService');
    }

    /**
     * @return \CmsIr\System\Model\LanguageTable
     */
    public function getLanguageTable($sm)
    {
        return $sm->get('CmsIr\System\Model\LanguageTable');
    }
}

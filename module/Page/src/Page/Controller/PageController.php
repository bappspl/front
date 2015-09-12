<?php

namespace Page\Controller;

use CmsIr\Page\Model\Page;
use CmsIr\Post\Model\Post;
use Zend\Db\Sql\Predicate\IsNotNull;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Adapter\Iterator;
use Zend\Paginator\Paginator;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

use Zend\Json\Json;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use CmsIr\Newsletter\Model\Subscriber;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;

class PageController extends AbstractActionController
{
    public function homeAction()
    {
        $this->layout('layout/home');

        $home = 'home';
        $this->layout()->setVariable('home', $home);

        $slider = $this->getSliderService()->findOneBySlug('slider-glowny');
        $items = $slider->getItems();

        $this->layout()->setVariable('items', $items);

        $lang = $this->getLangId($this->params()->fromRoute('lang'));

        if($lang->getId() == 1)
        {
            $url = 'historia';
        } else
        {
            $url = 'history';
        }

        $history = $this->getPageService()->findOneByUrlAndLangIdWithBlocks($url, $lang->getId());
        $posts = $this->getPostService()->findLastPostsByLangIdWithBlocks($lang->getId(), 'news', 'j F', 3);
        $opinions = $this->getPostService()->findLastPostsByLangIdWithBlocks($lang->getId(), 'opinion', 'j M');
        $categories = $this->getCategoryService()->findAllWithBlocks($lang->getId());
        $categories = array_values($categories);

        $viewParams = array();
        $viewParams['history'] = $history;
        $viewParams['lang'] = $lang->getUrlShortcut();
        $viewParams['posts'] = $posts;
        $viewParams['opinions'] = $opinions;
        $viewParams['categories'] = $categories;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function viewPageAction()
    {
        $this->layout('layout/home');

        $url = $this->params('url');

        $lang = $this->getLangId($this->params()->fromRoute('lang'));

        $page = $this->getPageService()->findOneByUrlAndLangIdWithBlocks($url, $lang->getId());

        if(empty($page))
        {
            $this->getResponse()->setStatusCode(404);
        }

        $viewParams = array();
        $viewParams['page'] = $page;
        $viewParams['lang'] = $lang->getUrlShortcut();
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);

        return $viewModel;
    }

    public function newsListAction()
    {
        $this->layout('layout/home');

        $lang = $this->getLangId($this->params()->fromRoute('lang'));

        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;

        $posts = $this->getPostService()->findLastPostsByLangIdWithBlocks($lang->getId(), 'news', 'j M');

        $paginator = new Paginator(new ArrayAdapter($posts));
        $paginator->setCurrentPageNumber($page)
            ->setItemCountPerPage(3);

        $viewParams = array();
        $viewParams['page'] = $page;
        $viewParams['paginator'] = $paginator;
        $viewParams['lang'] = $lang->getUrlShortcut();
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function viewNewsAction()
    {
        $this->layout('layout/home');

        $url = $this->params('url');

        $lang = $this->getLangId($this->params()->fromRoute('lang'));

        $post = $this->getPostService()->findOneByUrlAndLangIdWithBlocks($url, $lang->getId(), 'news');

        if(empty($post))
        {
            $this->getResponse()->setStatusCode(404);
        }

        $viewParams = array();
        $viewParams['post'] = $post;
        $viewParams['lang'] = $lang->getUrlShortcut();
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function saveSubscriberAjaxAction ()
    {
        $request = $this->getRequest();

        if ($request->isPost())
        {
            $uncofimerdStatus = $this->getStatusTable()->getOneBy(array('slug' => 'unconfirmed'));
            $uncofimerdStatusId = $uncofimerdStatus->getId();

            $email = $request->getPost('email');
            $confirmationCode = uniqid();
            $subscriber = new Subscriber();
            $subscriber->setEmail($email);
            $subscriber->setGroups(array());
            $subscriber->setConfirmationCode($confirmationCode);
            $subscriber->setStatusId($uncofimerdStatusId);

            $this->getSubscriberTable()->save($subscriber);
            $this->sendConfirmationEmail($email, $confirmationCode);

            $jsonObject = Json::encode($params['status'] = 'success', true);
            echo $jsonObject;
            return $this->response;
        }

        return array();
    }

    public function sendConfirmationEmail($email, $confirmationCode)
    {
        $transport = $this->getServiceLocator()->get('mail.transport')->findMailConfig();
        $from = $this->getServiceLocator()->get('mail.transport')->findFromMail();

        $message = new Message();
        $this->getRequest()->getServer();
        $message->addTo($email)
            ->addFrom($from)
            ->setEncoding('UTF-8')
            ->setSubject('Prosimy o potwierdzenie subskrypcji!')
            ->setBody("W celu potwierdzenia subskrypcji kliknij w link => " .
                $this->getRequest()->getServer('HTTP_ORIGIN') .
                $this->url()->fromRoute('newsletter-confirmation', array('code' => $confirmationCode)));
        $transport->send($message);
    }

    public function confirmationNewsletterAction()
    {
        $this->layout('layout/home');

        $request = $this->getRequest();
        $code = $this->params()->fromRoute('code');
        if (!$code) {
            return $this->redirect()->toRoute('home');
        }

        $viewParams = array();

        $activeStatus = $this->getStatusTable()->getOneBy(array('slug' => 'active'));
        $activeStatusId = $activeStatus->getId();

        $events = $this->getPostTable()->getBy(array('status_id' => $activeStatusId, 'category' => 'event'));
        foreach($events as $event)
        {
            $eventFiles = $this->getPostFileTable()->getOneBy(array('post_id' => $event->getId()));
            $event->setFiles($eventFiles);
        }
        $viewParams['banners'] = $events;


        $viewModel = new ViewModel();

        $subscriber = $this->getSubscriberTable()->getOneBy(array('confirmation_code' => $code));

        $confirmedStatus = $this->getStatusTable()->getOneBy(array('slug' => 'confirmed'));
        $confirmedStatusId = $confirmedStatus->getId();

        if($subscriber == false)
        {
            $viewParams['message'] = 'Nie istnieje taki użytkownik';
            $viewModel->setVariables($viewParams);
            return $viewModel;
        }

        $subscriberStatus = $subscriber->getStatusId();

        if($subscriberStatus == $confirmedStatusId)
        {
            $viewParams['message'] = 'Użytkownik już potwierdził subskrypcję';
        }

        else
        {
            $viewParams['message'] = 'Subskrypcja została potwierdzona';
            $subscriberGroups = $subscriber->getGroups();
            $groups = unserialize($subscriberGroups);

            $subscriber->setStatusId($confirmedStatusId);
            $subscriber->setGroups($groups);
            $this->getSubscriberTable()->save($subscriber);
        }

        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function contactFormAction()
    {
        $request = $this->getRequest();


        if ($request->isPost()) {
            $name = $request->getPost('name');
            $surname = $request->getPost('surname');
            $email = $request->getPost('email');
            $text = $request->getPost('text');
            $phone = $request->getPost('phone');

            $htmlMarkup = "Imię i Nazwisko: " . $name .  ' ' . $surname . "<br>" .
                "Telefon: " . $phone . "<br>" .
                "Telefon: " . $phone . "<br>" .
                "Treść: " . $text;

            $html = new MimePart($htmlMarkup);
            $html->type = "text/html";
            $html->charset = 'utf-8';

            $body = new MimeMessage();
            $body->setParts(array($html));

            $transport = $this->getServiceLocator()->get('mail.transport')->findMailConfig();
            $from = $this->getServiceLocator()->get('mail.transport')->findFromMail();

            $message = new Message();
            $this->getRequest()->getServer();
            $message->addTo('biuro@web-ir.pl')
                ->addFrom($from)
                ->setEncoding('UTF-8')
                ->setSubject('Wiadomość z formularza kontaktowego')
                ->setBody($body);
            $transport->send($message);

            $jsonObject = Json::encode($params['status'] = 'success', true);
            echo $jsonObject;
            return $this->response;
        }

        return array();
    }

    public function getSessionLangId()
    {
        $session = new Container();
        return isset($session->id) ? $session->id : 1;
    }

    public function getLangId($lang)
    {
        if(isset($lang))
        {
            $langId = $this->getLanguageTable()->getOneBy(array('url_shortcut' => $lang));
            return $langId;
        } else
        {
            return 1;
        }
    }

    /**
     * @return \CmsIr\Menu\Service\MenuService
     */
    public function getMenuService()
    {
        return $this->getServiceLocator()->get('CmsIr\Menu\Service\MenuService');
    }

    /**
     * @return \CmsIr\Slider\Service\SliderService
     */
    public function getSliderService()
    {
        return $this->getServiceLocator()->get('CmsIr\Slider\Service\SliderService');
    }

    /**
     * @return \CmsIr\Page\Service\PageService
     */
    public function getPageService()
    {
        return $this->getServiceLocator()->get('CmsIr\Page\Service\PageService');
    }

    /**
     * @return \CmsIr\Newsletter\Model\SubscriberTable
     */
    public function getSubscriberTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Newsletter\Model\SubscriberTable');
    }

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
    }

    /**
     * @return \CmsIr\Post\Model\PostTable
     */
    public function getPostTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Post\Model\PostTable');
    }

    /**
     * @return \CmsIr\File\Model\FileTable
     */
    public function getFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\FileTable');
    }

    /**
     * @return \CmsIr\Place\Model\PlaceTable
     */
    public function getPlaceTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Place\Model\PlaceTable');
    }

    /**
     * @return \CmsIr\System\Model\LanguageTable
     */
    public function getLanguageTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\LanguageTable');
    }

    /**
     * @return \CmsIr\Post\Service\PostService
     */
    public function getPostService()
    {
        return $this->getServiceLocator()->get('CmsIr\Post\Service\PostService');
    }

    /**
     * @return \CmsIr\Category\Service\CategoryService
     */
    public function getCategoryService()
    {
        return $this->getServiceLocator()->get('CmsIr\Category\Service\CategoryService');
    }
}
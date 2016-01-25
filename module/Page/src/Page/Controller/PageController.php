<?php

namespace Page\Controller;

use CmsIr\Page\Model\Page;
use CmsIr\Place\Model\Place;
use CmsIr\Post\Model\Post;
use Disc\Model\Disc;
use Performance\Model\Performance;
use Zend\Db\Sql\Predicate\IsNotNull;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\View\Model\JsonModel;
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
    public $langId = 1;

    public function homeAction()
    {
        $this->layout('layout/home');

        $slider = $this->getSliderService()->findOneBySlug('slider-glowny', $this->langId);
        $items = $slider->getItems();

        $team = $this->getTeamTable()->getBy(array('status_id' => 1));
        $videos = $this->getVideoService()->findOneByLangIdWithBlocks($this->langId);
        $posts = $this->getPostService()->findLastPostsByLangIdWithBlocks(1, 'news', 'Y-m-d', 3);

        $video = reset($videos);
        unset($videos[0]);

        $viewParams = array();
        $viewParams['items'] = $items;
        $viewParams['team'] = $team;
        $viewParams['video'] = $video;
        $viewParams['videos'] = $videos;
        $viewParams['posts'] = $posts;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function viewPageAction()
    {
        $this->layout('layout/home');

        $url = $this->params('url');

	    /* @var $page Page */
        $page = $this->getPageService()->findOneByUrlAndLangIdWithBlocks($url, 1);

        if(!isset($page)) {
            $this->getResponse()->setStatusCode(404);
        }

        $viewParams = array();
	    $viewParams['page'] = $page;
        $viewModel = new ViewModel();

//        var_dump($page);die;

        if($page->getType() == 1) {
            $viewModel->setTemplate('page/page/view-page-parts.phtml');
        } elseif($url == 'kontakt') {
            /* @var $place Place */
            $place = $this->getPlaceTable()->getOneBy(array(), 'id DESC');

            if(!empty($place)) {
                $viewParams['lat'] = $place->getLatitude();
                $viewParams['lng'] = $place->getLongitude();
            }

            $this->layout()->setVariable('kontakt', 1);

            $viewModel->setTemplate('page/page/contact.phtml');
        }

        $viewModel->setVariables($viewParams);

        return $viewModel;
    }

    public function newsListAction()
    {
        $this->layout('layout/home');

        $page = $this->params()->fromQuery('page') ? (int) $this->params()->fromQuery('page') : 1;

        $posts = $this->getPostService()->findLastPostsByLangIdWithBlocks(1, 'news', 'Y-m-d');

        $paginator = new Paginator(new ArrayAdapter($posts));
        $paginator->setCurrentPageNumber($page)
            ->setItemCountPerPage(5);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            if($page * 5 > count($posts)) {
                return null;
            }

            $htmlViewPart = new ViewModel();
            $htmlViewPart->setTerminal(true)
                ->setTemplate('partial/posts')
                ->setVariables(array(
                    'paginator' => $paginator
                ));

            $htmlOutput = $this->getServiceLocator()
                ->get('viewrenderer')
                ->render($htmlViewPart);

            $jsonObject = Json::encode(array(
                'html' => $htmlOutput,
                'count' => 5
            ), true);
            echo $jsonObject;
            return $this->response;
        }

        $viewParams = array();
        $viewParams['page'] = $page;
        $viewParams['paginator'] = $paginator;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function viewNewsAction()
    {
        $this->layout('layout/home');

        $url = $this->params('url');

        $post = $this->getPostService()->findOneByUrlAndLangIdWithBlocks($url, 1, 'news');

        if(empty($post)) {
            $this->getResponse()->setStatusCode(404);
        }

        $viewParams = array();
        $viewParams['post'] = $post;
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
        foreach($events as $event) {
            $eventFiles = $this->getPostFileTable()->getOneBy(array('post_id' => $event->getId()));
            $event->setFiles($eventFiles);
        }
        $viewParams['banners'] = $events;


        $viewModel = new ViewModel();

        $subscriber = $this->getSubscriberTable()->getOneBy(array('confirmation_code' => $code));

        $confirmedStatus = $this->getStatusTable()->getOneBy(array('slug' => 'confirmed'));
        $confirmedStatusId = $confirmedStatus->getId();

        if($subscriber == false) {
            $viewParams['message'] = 'Nie istnieje taki użytkownik';
            $viewModel->setVariables($viewParams);
            return $viewModel;
        }

        $subscriberStatus = $subscriber->getStatusId();

        if($subscriberStatus == $confirmedStatusId) {
            $viewParams['message'] = 'Użytkownik już potwierdził subskrypcję';
        } else {
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
            $name = $request->getPost('imie');
            $email = $request->getPost('email');
            $subject = $request->getPost('temat');
            $text = $request->getPost('wiadomosc');

            $htmlMarkup = "Imię : " . $name . "<br>" .
                "Email: " . $email . "<br>" .
                "Temat: " . $subject . "<br>" .
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

    public function performanceAction()
    {
        $this->layout('layout/home');

        $request = $this->getRequest();

        $performances = $this->getPerformanceTable()->getBy(array('status_id' => 1));

        $dates = array();

        /* @var $performance Performance */
        foreach($performances as $performance) {
            $date = $performance->getDate();
            $date = new \DateTime($date);
            $date = $date->format('Y-m-d');
            $dates[] = $date;
        }

        $page = $this->params()->fromQuery('page') ? (int) $this->params()->fromQuery('page') : 1;

        $paginator = new Paginator(new ArrayAdapter($performances));
        $paginator->setCurrentPageNumber($page)
            ->setItemCountPerPage(3);

        if ($request->isXmlHttpRequest()) {

            if($page * 3 > count($performances)) {
                return null;
            }

            $htmlViewPart = new ViewModel();
            $htmlViewPart->setTerminal(true)
                ->setTemplate('partial/performance')
                ->setVariables(array(
                    'paginator' => $paginator
                ));

            $htmlOutput = $this->getServiceLocator()
                ->get('viewrenderer')
                ->render($htmlViewPart);

            $jsonObject = Json::encode(array(
                'html' => $htmlOutput,
                'count' => 3
            ), true);
            echo $jsonObject;
            return $this->response;
        }

        $viewParams = array();
        $viewParams['paginator'] = $paginator;
        $viewParams['dates'] = $dates;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);

        return $viewModel;
    }

    public function performanceFormAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $name = $request->getPost('imie');
            $miejscowosc = $request->getPost('miejscowosc');
            $tel = $request->getPost('tel');
            $kod = $request->getPost('kod');
            $dodatki = $request->getPost('dodatki');
            $email = $request->getPost('email');
            $data = $request->getPost('data');

            $htmlMarkup = "Imię : " . $name . "<br>" .
                "Miejscowość: " . $miejscowosc . "<br>" .
                "Telefon: " . $tel . "<br>" .
                "Kod pocztowy: " . $kod. "<br>" .
                "Dodatkowe informacje: " . $dodatki. "<br>" .
                "Email: " . $email. "<br>" .
                "Data: " . $data. "<br>";

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
                ->setSubject('Wiadomość w sprawie koncertu')
                ->setBody($body);
            $transport->send($message);

            $jsonObject = Json::encode($params['status'] = 'success', true);
            echo $jsonObject;
            return $this->response;
        }

        return array();
    }

    public function galleryAction()
    {
        $this->layout('layout/home');

        $galleries = $this->getGalleryService()->findAll(1);
        $categories = $this->getCategoryTable()->getBy(array('type' => 'gallery'));

        $page = $this->params()->fromQuery('page') ? (int) $this->params()->fromQuery('page') : 1;

        $paginator = null;

        if(!empty($galleries)) {

            $paginator = new Paginator(new ArrayAdapter($galleries));
            $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage(6);

            $request = $this->getRequest();

            if ($request->isXmlHttpRequest()) {

                if($page * 6 > count($galleries)) {
                    return null;
                }

                $htmlViewPart = new ViewModel();
                $htmlViewPart->setTerminal(true)
                    ->setTemplate('partial/gallery')
                    ->setVariables(array(
                        'paginator' => $paginator
                    ));

                $htmlOutput = $this->getServiceLocator()
                    ->get('viewrenderer')
                    ->render($htmlViewPart);

                $jsonObject = Json::encode(array(
                    'html' => $htmlOutput,
                    'count' => 6
                ), true);
                echo $jsonObject;
                return $this->response;
            }

        }

        $viewParams = array();
        $viewParams['paginator'] = $paginator;
        $viewParams['categories'] = $categories;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);

        return $viewModel;
    }

    public function viewOneGalleryAction()
    {
        $this->layout('layout/home');

        $id = $this->params()->fromRoute('id');

        $gallery = $this->getGalleryService()->findOne(1, $id);

        $viewParams = array();
        $viewParams['gallery'] = $gallery;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);

        return $viewModel;
    }

    public function discAction()
    {
        $this->layout('layout/home');

        $discs = $this->getDiscService()->findAll();
        $categories = $this->getCategoryTable()->getBy(array('type' => 'music'));

//        $page = $this->params()->fromQuery('page') ? (int) $this->params()->fromQuery('page') : 1;
//
//        $paginator = null;
//
//        if(!empty($discs)) {
//
//            $paginator = new Paginator(new ArrayAdapter($discs));
//            $paginator->setCurrentPageNumber($page)
//                ->setItemCountPerPage(3);
//
//            $request = $this->getRequest();
//
//            if ($request->isXmlHttpRequest()) {
//
//                if ($page * 3 > count($discs)) {
//                    return null;
//                }
//
//                $htmlViewPart = new ViewModel();
//                $htmlViewPart->setTerminal(true)
//                    ->setTemplate('partial/disc')
//                    ->setVariables(array(
//                        'paginator' => $paginator
//                    ));
//
//                $htmlOutput = $this->getServiceLocator()
//                    ->get('viewrenderer')
//                    ->render($htmlViewPart);
//
//                $jsonObject = Json::encode(array(
//                    'html' => $htmlOutput,
//                    'count' => 3
//                ), true);
//                echo $jsonObject;
//                return $this->response;
//            }
//
//        }

        $viewParams = array();
        $viewParams['discs'] = $discs;
        $viewParams['categories'] = $categories;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);

        return $viewModel;
    }

    public function viewDiscAction()
    {
        $this->layout('layout/home');

        $id = $this->params()->fromRoute('id');

        /* @var $disc Disc */
        $disc = $this->getDiscService()->findOneBy(array('id' => $id));

        $position = $disc->getPosition();

        $prevDisc = $this->getDiscTable()->getOneBy(array('position' => $position - 1));
        $nextDisc = $this->getDiscTable()->getOneBy(array('position' => $position + 1));

        $viewParams = array();
        $viewParams['disc'] = $disc;
        $viewParams['prev'] = $prevDisc;
        $viewParams['next'] = $nextDisc;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);

        return $viewModel;
    }

    public function viewPersonAction()
    {
        $this->layout('layout/home');

        $id = $this->params()->fromRoute('id');

        /* @var $disc Disc */
        $person = $this->getTeamTable()->getOneBy(array('id' => $id));

        $viewParams = array();
        $viewParams['person'] = $person;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);

        return $viewModel;
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
     * @return \Team\Model\TeamTable
     */
    public function getTeamTable()
    {
        return $this->getServiceLocator()->get('Team\Model\TeamTable');
    }

    /**
     * @return \CmsIr\Video\Service\VideoService
     */
    public function getVideoService()
    {
        return $this->getServiceLocator()->get('CmsIr\Video\Service\VideoService');
    }

    /**
     * @return \CmsIr\Post\Service\PostService
     */
    public function getPostService()
    {
        return $this->getServiceLocator()->get('CmsIr\Post\Service\PostService');
    }

    /**
     * @return \Performance\Model\PerformanceTable
     */
    public function getPerformanceTable()
    {
        return $this->getServiceLocator()->get('Performance\Model\PerformanceTable');
    }

    /**
     * @return \CmsIr\File\Service\GalleryService
     */
    public function getGalleryService()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Service\GalleryService');
    }

    /**
     * @return \CmsIr\Category\Model\CategoryTable
     */
    public function getCategoryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Category\Model\CategoryTable');
    }

    /**
     * @return \Disc\Model\DiscTable
     */
    public function getDiscTable()
    {
        return $this->getServiceLocator()->get('Disc\Model\DiscTable');
    }

    /**
     * @return \Disc\Service\DiscService
     */
    public function getDiscService()
    {
        return $this->getServiceLocator()->get('Disc\Service\DiscService');
    }
}
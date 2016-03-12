<?php

namespace Page\Controller;

use CmsIr\Page\Model\Page;
use CmsIr\Post\Model\Post;
use Page\Mail;
use Zend\Db\Sql\Predicate\IsNotNull;
use Zend\Mvc\Controller\AbstractActionController;
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
        $viewParams = array();
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function viewPageAction()
    {
        $this->layout('layout/home');

        $slug = $this->params('slug');

        $page = $this->getPageService()->findOneBySlug($slug);
        if(empty($page)) {
            $this->getResponse()->setStatusCode(404);
        }

        $viewParams = array();
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);

        return $viewModel;
    }

    public function newsListAction()
    {
        $this->layout('layout/home');

        $viewParams = array();
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function viewNewsAction()
    {
        $this->layout('layout/home');

        $slug = $this->params('slug');

        $viewParams = array();
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
            $name = $request->getPost('name');
            $surname = $request->getPost('surname');
            $email = $request->getPost('email');
            $text = $request->getPost('text');
            $phone = $request->getPost('phone');

            $htmlMarkup = "Imię i Nazwisko: " . $name .  ' ' . $surname . "<br>" .
                "Telefon: " . $phone . "<br>" .
                "Email: " . $email . "<br>" .
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
}

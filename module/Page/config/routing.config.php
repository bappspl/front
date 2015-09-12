<?php

return array(
    'home' => array(
        'type' => 'Zend\Mvc\Router\Http\Segment',
        'options' => array(
            'route'    => '/[:lang]',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'home',
                'lang'       => 'pl'
            ),
        ),
    ),
    'view-page' => array(
        'type' => 'Zend\Mvc\Router\Http\Segment',
        'options' => array(
            'route'    => '[/:lang]/strona/:url',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'viewPage',
            ),
        ),
    ),
    'news-list' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '[/:lang]/aktualnosci[/page/:page]',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'newsList',
            ),
        ),
    ),
    'view-news' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '[/:lang]/aktualnosci/:url',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'viewNews',
            ),
            'constraints' => array(
                'url' => '[a-zA-Z0-9_-]+'
            ),
        ),
    ),
    'save-subscriber' => array(
        'type' => 'Zend\Mvc\Router\Http\Literal',
        'options' => array(
            'route'    => '/save-new-subscriber',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'saveSubscriberAjax',
            ),
        ),
    ),
    'contact-form' => array(
        'type' => 'Zend\Mvc\Router\Http\Literal',
        'options' => array(
            'route'    => '/contact-form',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'contactForm',
            ),
        ),
    ),
    'newsletter-confirmation' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/newsletter-confirmation/:code',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'confirmationNewsletter',
            ),
            'constraints' => array(
                'code' => '[a-zA-Z0-9_-]+'
            ),
        ),
    ),
);
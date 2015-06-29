<?php

return array(
    'home' => array(
        'type' => 'Zend\Mvc\Router\Http\Literal',
        'options' => array(
            'route'    => '/',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'home',
            ),
        ),
    ),
    'view-page' => array(
        'type' => 'Zend\Mvc\Router\Http\Segment',
        'options' => array(
            'route'    => '/strona/:slug',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'viewPage',
            ),
        ),
    ),
    'news-list' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/aktualnosci',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'newsList',
            ),
        ),
    ),
    'view-news' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/aktualnosci/:slug',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'viewNews',
            ),
            'constraints' => array(
                'slug' => '[a-zA-Z0-9_-]+'
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
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
            'route'    => '/strona/:url',
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
            'route'    => '/aktualnosci/:url',
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
    'view-performance' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/koncerty',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'performance',
            )
        ),
    ),
    'performance-form' => array(
        'type' => 'Zend\Mvc\Router\Http\Literal',
        'options' => array(
            'route'    => '/performance-form',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'performanceForm',
            ),
        ),
    ),
    'view-gallery' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/galeria',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'gallery',
            )
        ),
    ),
    'disc-list' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/dyskografia',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'disc',
            )
        ),
    ),
    'view-disc' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/dyskografia/:id',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'viewDisc',
            ),
            'constraints' => array(
                'id' => '[0-9_-]+'
            ),
        ),
    ),
    'view-person' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/zespol/:id',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'viewPerson',
            ),
            'constraints' => array(
                'id' => '[0-9_-]+'
            ),
        ),
    ),
    'view-one-gallery' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/galeria/:id',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'viewOneGallery',
            ),
            'constraints' => array(
                'id' => '[0-9_-]+'
            ),
        ),
    ),
);
<?php
return array(
    'acl' => array(
        'roles' => array(
            'guest'   => null,
            'user'  => 'guest',
            'admin'  => 'user',
            'superadmin'  => 'admin'
        ),
        'resources' => array(
            'allow' => array(
                'Application\Controller\Index' => array(
                    'all'	=> 'guest',
                ),
                'Page\Controller\Page' => array(
                    'all'	=> 'guest',
                ),

                    // CMS
                'CmsIr\Authentication\Controller\Index' => array(
                    'all'	=> 'guest',
                ),
                'CmsIr\Dashboard\Controller\Index' => array(
                    'all'	=> 'user',
                ),
                'CmsIr\Menu\Controller\Menu' => array(
                    'all'	=> 'admin',
                ),
                'CmsIr\Users\Controller\Users' => array(
                    'all'	=> 'admin',
                ),
                'CmsIr\Slider\Controller\Slider' => array(
                    'all'	=> 'admin',
                ),
                'CmsIr\Newsletter\Controller\Newsletter' => array(
                    'all'	=> 'admin',
                ),
                'CmsIr\Newsletter\Controller\Subscriber' => array(
                    'all'	=> 'admin',
                ),
                'CmsIr\Page\Controller\Page' => array(
                    'all'	=> 'admin',
                ),
                'CmsIr\Post\Controller\Post' => array(
                    'all'	=> 'admin',
                ),
                'CmsIr\System\Controller\System' => array(
                    'createThumb'	=> 'guest',
                    'saveEditorImages'	=> 'guest',
                    'settings'	=> 'admin',
                    'mailConfig'	=> 'admin',
                    'sendTestEmail'	=> 'user',
                ),
                'CmsIr\File\Controller\Gallery' => array(
                    'all'	=> 'admin',
                ),
                'CmsIr\Banner\Controller\Banner' => array(
                    'all'	=> 'admin',
                ),
                'CmsIr\Place\Controller\Place' => array(
                    'all'	=> 'admin',
                ),
                'CmsIr\Dictionary\Controller\Dictionary' => array(
                    'all'	=> 'admin',
                ),
                'CmsIr\File\Controller\File' => array(
                    'all'	=> 'admin',
                ),
                'CmsIr\Category\Controller\Category' => array(
                    'all'	=> 'admin',
                ),
                'CmsIr\Tag\Controller\Tag' => array(
                    'all'	=> 'admin',
                ),
                'CmsIr\Video\Controller\Video' => array(
                    'all'	=> 'admin',
                )
            )
        )
    )
);

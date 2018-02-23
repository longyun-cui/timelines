<?php

    return [

        'host' => [

            'local' => [
                'root' => 'http://courses.com',
                'cdn' => 'http://cdn.courses.com',
            ],

            'online' => [
                'root' => 'http://live8.pub',
                'cdn' => 'http://cdn.live8.pub',
            ],
        ],

        'MailService' => 'http://live2.pub:8088',

        'zh' => [
            'course' => '课程',
        ],

        'view' => [
            'front' => [
                'template' => 'online',
                'index' => 'vipp',
                'list' => 'vipp',
                'detail' => 'vipp'
            ],
        ],

        'website' => [
            'front' => [
                'prefix' => 'org'
            ],
        ],

        'common' => [
            'module' => [
                0 => 'default',
                1 => 'product',
                2 => 'article',
                3 => 'activity',
                4 => 'survey',
                5 => 'slide',
            ],

            'sort' => [
                0 => 'default',
                1 => 'product',
                2 => 'article',
                3 => 'activity',
                4 => 'survey',
                5 => 'slide',
            ],
        ],


    ];

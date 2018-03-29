<?php

    return [

        'host' => [

            'local' => [
                'root' => 'http://lines.com',
                'cdn' => 'http://cdn.lines.com',
            ],

            'online' => [
                'root' => 'http://tinyline.cn',
                'cdn' => 'http://cdn.tinyline.cn',
            ],
        ],

        'MailService' => 'http://cuilongyun.win:8088',

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

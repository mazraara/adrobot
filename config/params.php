<?php

return [
    'supportEmail' => 'admin@azydev.com',
    'productName' => 'Adrobot',
    'adminEmail' => 'admin@azydev.com',
    'salt' => '$r!lanka',
    'allowEmpty' => true,
    'accessDeniedUrl' => ['site/access-denied'],
    'defaultTimeZone' => 'Asia/Colombo',
    'tempPath' => dirname(__DIR__) . '/runtime/temp/',
    'webTempPath' => dirname(__DIR__) . '/web/temp/',
    'webTempUrl' => 'http://localhost/Adrobot/web/temp/',
    'consoleCmdPath' => '/var/www/html/Adrobot/yii',
    'msgs' => [
        'instructions' => 'Sorry didn\'t get that {link}',
    ],
    'copyright'=>'Azraar Azward',
];

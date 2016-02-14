<?php
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'homeUrl' => ['site/index'],
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! Insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'azuyiouio',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'app\components\WebUser',
            'identityClass' => 'app\models\Auth',
            'enableAutoLogin' => true,
            'loginUrl' => ['site/login']
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mandrillapp.com',
                'username' => 'azraar90@gmail.com',
                'password' => 'I0sPajfMpB6ot205u5cB-A',
                'port' => '587',
                'encryption' => 'tls',
//                'host' => 'in.mailjet.com',
//                'username' => '882e1f5a7e8393288b0e622a95ed3dc7',
//                'password' => '1de1796d95a02e1166e516740db0f5b9',
//                'port' => '25',
//                'encryption' => 'tls',
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'util' => [
            'class' => 'app\components\Util',
        ],
        'appLog' => [
            'class' => 'app\components\AppLogger',
            'logType' => 1,
            'logParams' => [
                1 => [
                    'logPath' => dirname(__DIR__) . '/runtime/logs/',
                    'logName' => '-activity.log',
                    'logLevel' => 3, // Take necessary value from apploger class
                    'logSocket' => '',
                    'isConsole' => false
                ]
            ]
        ],
        'view' => [
            'class' => 'app\components\View',
            'theme' => [
                'pathMap' => ['@app/views' => '@webroot/themes/default/views'],
                'baseUrl' => '@web/themes/default',
                'basePath' => '@app/web/themes/default',
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => []
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            //'showScriptName' => false,
            'rules' => [
                'book/read/<t>/<c>' => 'book/read',
                'site/registerInterest' => 'site/register-interest',
            ]
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'modules' => [
    ],
    'aliases' => [
        '@defaultTheme' => '/themes/default/',
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
//    $config['bootstrap'][] = 'debug';
//    $config['modules']['debug'] = [
//        'class' => 'yii\debug\Module',
//    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;

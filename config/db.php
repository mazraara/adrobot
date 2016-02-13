<?php
if (YII_ENV_DEV) {
    // Dev environment
    $dbParms = [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=Adrobot',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
    ];
} else if (YII_ENV_PROD) {
    // Production environment
    $dbParms = [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=Adrobot',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
    ];
}

return $dbParms;

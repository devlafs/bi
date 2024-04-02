<?php

return
        [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . constant('DB_HOST_MAIN') . ';dbname=' . constant('DB_DATABASE_MAIN'),
            'username' => constant('DB_USERNAME_MAIN'),
            'password' => constant('DB_PASSWORD_MAIN'),
            'charset' => 'utf8'
];

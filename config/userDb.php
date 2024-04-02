<?php

return
        [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . constant('DB_HOST_USER') . ';dbname=' . constant('DB_DATABASE_USER'),
            'username' => constant('DB_USERNAME_USER'),
            'password' => constant('DB_PASSWORD_USER'),
            'charset' => 'utf8'
];

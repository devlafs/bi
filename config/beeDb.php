<?php

return
        [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . constant('DB_HOST_BEE') . ';dbname=' . constant('DB_DATABASE_BEE'),
            'username' => constant('DB_USERNAME_BEE'),
            'password' => constant('DB_PASSWORD_BEE'),
            'charset' => 'utf8'
];

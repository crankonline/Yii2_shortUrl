<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=yii2_shorturl-mysql;port=3306;dbname=ShortUrl',
    'username' => 'mysql',
    'password' => 'root',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];

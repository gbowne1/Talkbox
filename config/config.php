<?php
return [
    'driver'   => 'mysql',   // mysql | mariadb | pgsql | sqlite | mongodb
    'host'     => '127.0.0.1',
    'port'     => 3306,
    'dbname'   => 'talkbox',
    'username' => 'root',
    'password' => 'secret',
    'path'     => __DIR__ . '/../data/talkbox.sqlite' // for SQLite
];

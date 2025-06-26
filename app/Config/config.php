<?php

define('APP_NAME', 'MVC');

// Required php extensions
define('REQ_PHP_EXT', [
     'fileinfo', 'gd', 'gettext', 'mbstring', 'exif', 'mysqli', 'pdo_mysql', 'pdo_sqlite'
]);

// DEBUG mode : true in dev, false in prod
define('DEBUG', true);

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    define('BASE_APP', 'http://localhost/workspace/Ecoride-Dev/'.APP_NAME.'/');
    define('BASE_URL', 'http://localhost/workspace/Ecoride-Dev/'.APP_NAME."/public/");

    // Database config (localhost)
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'grafikart');

} else {
    define('ROOT_APP', 'https://my-app.com/');
    define('ROOT_URL', 'https://my-app.com/public/');

    // Database config 
    define('DB_HOST', 'host');
    define('DB_USER', 'username');
    define('DB_PASS', 'psw');
    define('DB_NAME', 'db');
}
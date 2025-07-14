<?php
/* app name, mail, domain */
define('APP_NAME', 'Ecoride');
define('APP_MAIL', 'jose.ecoride.2025@gmail.com');

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    define('DOMAIN', 'localhost');
} else {    
    define('DOMAIN', 'tony-s-pro.alwaysdata.net');
}



/* SESSION */
ini_set(option: 'session.use_only_cookies', value: 1); //session id thru cookies, not uri
ini_set(option: 'session.use_strict_mode', value: 1);  //only use own session id + more complexe id
session_set_cookie_params([
    'lifetime' => 3600,         // 1h
    'domain' => DOMAIN,
    'path' => '/',              // any path in website
    'secure' => true,           // https only
    'httponly' => true          // no script access from client
]);

session_start();

/* recreate id in a more complexe/secure way */
/* recreate id if user comes back before 1h (lifetime) but after 30min */
if (!isset($_SESSION['last_regeneration'])) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
} else {
    $interval = 60*30;
    if (time()-$_SESSION['last_regeneration'] >= $interval) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}


/* misc */
date_default_timezone_set('Europe/Paris');



/* Required php extensions */
define('REQ_PHP_EXT', [
     'fileinfo', 'gd', 'gettext', 'mbstring', 'exif', 'mysqli', 'pdo_mysql', 'pdo_sqlite', 'php_mongodb.dll'
]);



/* DEBUG mode : true in dev, false in prod */
define('DEBUG', true);

/* Debug Mode */
error_reporting(E_ALL);
DEBUG ? ini_set('display_errors', 1) : ini_set('display_errors', 0);



/* DB */
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    
    define('BASE_APP', 'http://localhost/workspace/'.APP_NAME.'/');
    define('BASE_URL', 'http://localhost/workspace/'.APP_NAME."/public/");

    /* Database config (localhost) */
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'ecoride');

    /* MongoDB Database config (localhost) (no user@password by default on local) */
    define('MDB_HOST', 'localhost');
    define('MDB_PORT', '27017');
    define('MDB_USER', '');
    define('MDB_PASS', '');
    define('MDB_NAME', 'ecoride');

} else {
    define('BASE_APP', 'https://tony-s-pro.alwaysdata.net/');
    define('BASE_URL', 'https://tony-s-pro.alwaysdata.net/public/');

    /* Database config */
    define('DB_HOST', 'mysql-tony-s-pro.alwaysdata.net');
    define('DB_USER', '409092_jose2025');
    define('DB_PASS', 'STUDI_ecoride25');
    define('DB_NAME', 'tony-s-pro_ecoride');

    /* MongoDB Database config for (check .env) */
    define('MDB_NAME', 'ecoride');
}



/* SMTP */
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587); //587->tls, 465->ssl
define('SMTP_USER', 'jose.ecoride.2025@gmail.com');
define('SMTP_PASS', 'opzdkdkeuonqodna'); //app password 16char to set up in account after 2step auth
define('SMTP_FROM', 'jose.ecoride.2025@gmail.com');
define('SMTP_FROM_NAME', 'Ecoride Contact');


/* Legal mentions / cookies policy */
define('LEGAL_HOST', 'ALWAYSDATA, SARL');
define('LEGAL_HOST_ADRSS', '91 rue du Faubourg Saint Honoré - 75008 Paris');
define('LEGAL_HOST_MAIL', 'contact@alwaysdata.com');
define('LEGAL_HOST_PHONE', '+33 1 84 16 23 40');
define('LEGAL_DPO_NAME', '"A l’attention du Délégué à la Protection des Données (DPO)"');
define('LEGAL_DPO_MAIL', ' dpo@alwaysdata.com');
define('LEGAL_HOST_POLICY', 'https://www.alwaysdata.com/fr/mentions-legales');
define('LEGAL_CONTRIB', '');
define('LEGAL_FR_1', 'https://www.legifrance.gouv.fr/loda/id/JORFTEXT000000801164#LEGIARTI000042038977');
define('LEGAL_FR_2', 'https://www.legifrance.gouv.fr/codes/article_lc/LEGIARTI000032655082');
define('LEGAL_FR_4', 'https://www.legifrance.gouv.fr/loda/id/JORFTEXT000000886460');

define('ADMIN_ID', 5);


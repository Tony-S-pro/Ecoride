<?php

define('APP_NAME', 'Ecoride');
define('APP_MAIL', 'exemple@mail.com');

// Required php extensions
define('REQ_PHP_EXT', [
     'fileinfo', 'gd', 'gettext', 'mbstring', 'exif', 'mysqli', 'pdo_mysql', 'pdo_sqlite'
]);

// DEBUG mode : true in dev, false in prod
define('DEBUG', true);

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    define('BASE_APP', 'http://localhost/workspace/'.APP_NAME.'/');
    define('BASE_URL', 'http://localhost/workspace/'.APP_NAME."/public/");

    // Database config (localhost)
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'ecoride');

    // Database config (localhost) for MongoDB
    // no user@password by default on local
    define('MDB_HOST', 'localhost');
    define('MDB_PORT', '27017');
    define('MDB_USER', '');
    define('MDB_PASS', '');
    define('MDB_NAME', 'ecoride');

} else {
    define('ROOT_APP', 'https://my-app.com/');
    define('ROOT_URL', 'https://my-app.com/public/');

    // Database config 
    define('DB_HOST', 'host');
    define('DB_USER', 'username');
    define('DB_PASS', 'psw');
    define('DB_NAME', 'db');

    // Database config for MongoDB
    // check .env
    define('MDB_NAME', 'ecoride');
}

/*SMTP*/
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587); //587->tls, 465->ssl
define('SMTP_USER', 'jose.ecoride.2025@gmail.com');
define('SMTP_PASS', '<REPLACE THIS TO TEST'); //app password 16char to set up in account after 2step auth
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


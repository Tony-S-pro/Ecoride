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

} else {
    define('ROOT_APP', 'https://my-app.com/');
    define('ROOT_URL', 'https://my-app.com/public/');

    // Database config 
    define('DB_HOST', 'host');
    define('DB_USER', 'username');
    define('DB_PASS', 'psw');
    define('DB_NAME', 'db');
}

/* Legal mentions / cookies policy */
define('LEGAL_HOST', 'Nom_Hebergeur');
define('LEGAL_HOST_ADRSS', 'Adresse_Hebergeur');
define('LEGAL_HOST_MAIL', 'hebergeur@mail.com');
define('LEGAL_HOST_PHONE', '+33 1 23 45 67 89');
define('LEGAL_DPO_NAME', 'Nom_du_DPO');
define('LEGAL_DPO_MAIL', 'adresse_du_DPO@mail.com');
define('LEGAL_HOST_POLICY', 'https://www.hebergeur.com/Politique-de-Confidentialite');
define('LEGAL_CONTRIB', 'Contributeurs, webmaster, crédit photos, ...');
define('LEGAL_FR_1', 'https://www.legifrance.gouv.fr/loda/id/JORFTEXT000000801164#LEGIARTI000042038977');
define('LEGAL_FR_2', 'https://www.legifrance.gouv.fr/codes/article_lc/LEGIARTI000032655082');
define('LEGAL_FR_4', 'https://www.legifrance.gouv.fr/loda/id/JORFTEXT000000886460');


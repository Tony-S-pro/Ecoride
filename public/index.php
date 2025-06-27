<?php
session_start([
    'cookie_lifetime' => (30*24*60*60),
    'gc_maxlifetime' => (30*24*60*60)
]);

/* Path to this file (to check for direct file access)*/
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);

require '../config/config.php';

/* Debug Mode */
error_reporting(E_ALL);
DEBUG ? ini_set('display_errors', 1) : ini_set('display_errors', 0);

require_once __DIR__ . '/../app/autoload.php'; //replaced by composer/autoload
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/index.php';



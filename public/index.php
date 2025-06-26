<?php
session_start();

/* Path to this file (to check for direct file access)*/
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);

require '../app/Config/config.php';

/* Debug Mode */
error_reporting(E_ALL);
DEBUG ? ini_set('display_errors', 1) : ini_set('display_errors', 0);

require_once __DIR__ . '/../app/autoload.php';
require_once __DIR__ . '/../app/index.php';



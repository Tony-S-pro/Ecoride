<?php
require_once '../config/config.php';

/* Path to this file (to check for direct file access)*/
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);

require_once __DIR__ . '/../app/autoload.php'; //replaced by composer/autoload
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../config');
$dotenv->load();

require_once __DIR__ . '/../app/index.php';



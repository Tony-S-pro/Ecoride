<?php
spl_autoload_register(function ($className) {
    // Turns namespace into path
    $className = str_replace('App\\', 'app/', $className); 

    $className = str_replace('\\', '/', $className);

    $filePath = dirname(__DIR__) . '/' . $className . '.php';

    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
        echo "File not found: $filePath";
    }    
});
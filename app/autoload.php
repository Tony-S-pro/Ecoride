<?php
/**
 * Autoloader PSR-4
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(function ($class) {

    // project's namespace prefix
    $prefix = 'App\\';

    // base directory matching namespace prefix
    $base_dir = BASE_APP . 'app/';

    // check if class uses the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory
    // replace namespace separators with dir separators in the relative class name
    // append .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require_once $file;
    }
});
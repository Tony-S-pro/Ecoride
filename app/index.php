<?php

defined('ROOT_PATH') OR exit("You don't have permission to access this resource.");

// URL from $_GET (ex: /user/show/12 into ["user", "edit", "12"])
$url = $_GET['url'] ?? '';
$url = trim($url, '/');
$segments = explode('/', $url);

// Call NameController/method(default index). If empty, call HomeController/index
$controllerName = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'HomeController';
$methodName = $segments[1] ?? 'index';

// Validating class/method name (no dangerous chars to avoid SQL injections SQL, XSS, etc…)
if (!preg_match('/^[A-Z][a-zA-Z0-9]*Controller$/', $controllerName)) {
    require_once __DIR__ . '/Views/error404.php';
    exit();
}
if (!preg_match('/^[a-zA-Z0-9_]+$/', $methodName)) {
    require_once __DIR__ . '/Views/error404.php';
    exit();
}

// Controller/methods white list (to avoid access to sensitive controllers)
$allowedControllers = [
    'HomeController', 
    'MentionsController',
    'SignupController', 
    'LoginController',
    'LogoutController',
    'DashboardController',
    'CarpoolsController',
    'UserController',
    'ContactController',
    'TestController', 'RandomController'];
$allowedMethods = ['index', 'cookies', 'register', 'login', 'search', 'message', 'thanks', 'driver'];

if (!in_array($controllerName, $allowedControllers) || !in_array($methodName, $allowedMethods)) {
    require_once __DIR__ . '/Views/error404.php';
    exit();
}

// NameController Namespace
$controllerClass = 'App\\Controllers\\' . $controllerName;

if (class_exists($controllerClass)) {
    $controller = new $controllerClass();

    if (method_exists($controller, $methodName)) {
        // Call method and pass param (ex: /user/show/12)
        // note: sanitize & validate in the controllers
        $params = array_slice($segments, 2);
        call_user_func_array([$controller, $methodName], $params);
    } else {
        require_once __DIR__ . '/Views/error404.php';
    }
} else {
    require_once __DIR__ . '/Views/error404.php';
}
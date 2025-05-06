<?php
require_once __DIR__ . '/../app/init.php';
require_once __DIR__ . '/../routes/web.php';

//$request = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD']; 

if(isset($routes[$method][$uri])) {
list($controller, $action) = explode('@', $routes[$method][$uri]);

require_once __DIR__ . '/../app/controllers/' . $controller . '.php';

$controllerInstance = new $controller;
$controllerInstance->$action();
} else {
    http_response_code(404);
    echo "404 Not Found";
}



// if(array_key_exists($request, $routes)) {

//     $route = explode('@', $routes[$request]);
//     $controllerName = $route[0];
//     $methodName = $route[1];
//     $controller = new $controllerName();
//     $controller->$methodName();

// } else {
//     echo "404 - Page not found";
// }
<?php 


// BASE URL

function base_url($path = '') {

    if(defined('BASE_URL')) {
        return BASE_URL . ltrim($path, DIRECTORY_SEPARATOR);
    }

    //https:// or http:// 
    $protocol = (!empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS']) !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? "https://" : "http://";

    // domainame.com
    $host = $_SERVER['HTTP_HOST'];
    // domainame.com/blog
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), DIRECTORY_SEPARATOR);

    return $protocol . $host . $base . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
}

function base_path($path = '') {
    return realpath(__DIR__ . '/../' . '/' . ltrim($path, '/'));
}

function views_path($path = '') {
    return base_path('app/views/' . ltrim($path, '/'));
}

function redirect($path = '', $queryParam = []) {
    $url = base_url($path);
    if(!empty($queryParam)){
        $url .= '?' . http_build_query($queryParam);
    }
    header("Location: " . $url);
    exit();
}

function render($view, $data = [], $layout = 'layout') {
    extract($data);
    ob_start();
    require views_path($view . '.php');
    $content = ob_get_clean();
    require views_path($layout . '.php');
}

function config($key) {
    $config = require base_path('config/config.php');

    $keys = explode('.', $key);
    $value = $config;

    foreach($keys as $k) {
        if(!isset($value[$k])) {
            throw new Exception("Config key '{$k}' not found");
        }
        $value = $value[$k];
    }
    return $value;
}

function sanitize($values) {
    return htmlspecialchars(strip_tags($values));
}

function isLoggedIn () {
    return isset($_SESSION['id']);
}

function getUserFullName() {
    if(isset($_SESSION['first_name']) && isset($_SESSION['last_name'])) {
        return $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
    } else {
        return $_SESSION['username'];
    }
}
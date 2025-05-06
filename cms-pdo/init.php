<?php
    // Autoload classes
require_once "autoloader.php";

    // Start Session    
    session_start();
require_once 'config/config.php';

    // Load database  
require_once "classes/Database.php";
 
    // Include helper functions
require_once "helper.php";

    // Define  global constants
define('APP_NAME', 'CMS PDO System');
define("PROJECT_DIR", "cms-pdo")
?>
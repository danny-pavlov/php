<?php

class AuthMiddleware {

    static public function isAuthenticated() {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return isset ($_SESSION['id']) && !empty($_SESSION['id']);
    }

    static public function requiredLogin() {
        if(!self::isAuthenticated()) {
            redirect('/user/login');
        }
    }

}
<?php

class Auth {

    public static function isLogin(){
        return isset($_SESSION['isLogin']) && $_SESSION['isLogin'];
    }

    public static function login(){
        session_regenerate_id(true);
        $_SESSION['isLogin'] = true;
    }

    public static function logout(){

        // Unset all of the session variables.
        $_SESSION = [];

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();


    }


}
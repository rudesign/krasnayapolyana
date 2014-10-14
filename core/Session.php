<?php

class Session{

    public static function set(){
        // session cookie's lifetime: one day by default
        $lifetime = isset(Settings::$data->sessionCookiesLifetime) ? Settings::$data->sessionCookiesLifetime : 86400;

        session_set_cookie_params($lifetime, "/", ".".$_SERVER['HTTP_HOST']);
        session_start();

        //if(session_status() !== PHP_SESSION_ACTIVE) throw new Error('Cannot start session', true);
    }

    public static function destroy(){
        //if(session_status() === PHP_SESSION_ACTIVE) session_destroy();
        if(Users::$current) session_destroy();
    }
}
?>
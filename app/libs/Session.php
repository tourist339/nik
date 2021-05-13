<?php

class Session{
    public static function isLoggedIn(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if(!isset($_SESSION["id"])){
            return false;
        }
        return true;
    }

    public static function getUser(){
        return $_SESSION["id"];
    }
}

?>

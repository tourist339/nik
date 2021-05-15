<?php

class Session{
    const ID="id";
    const EMAIL="email";
    const ACTIVE_PROP="active_prop";
    const LOGIN_TYPE="login_type";
    public static function startSession(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    public static function isLoggedIn(){
        Session::startSession();
        if(!isset($_SESSION["id"])){
            return false;
        }
        return true;
    }

    public static function getUserId(){
        self::startSession();
        return $_SESSION["id"];

    }

    public static function storeOpenedProperty($prop_id){
        self::startSession();

        $_SESSION["active_prop"]=prop_id;
    }
    public static function getOpenedProperty(){
        self::startSession();
        if(isset($_SESSION["active_prop"])){
            return $_SESSION["active_prop"];
        }else{
            return false;
        }

    }

    public static function getUser(){
        Session::startSession();
        return $_SESSION["id"];
    }

    public static function logout(){
        session_start();
        session_unset();
        session_destroy();
    }
}

?>

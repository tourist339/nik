<?php
class Session{
    public static function isLoggedIn(){
        session_start();
        if(!isset($_SESSION["id"])){
            return false;
        }
        return true;


    }
}

?>

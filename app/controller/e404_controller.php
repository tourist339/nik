<?php


class e404_controller
{
    public function __construct($errormsg="File Not Found")
    {
        echo $errormsg;
        exit();
    }
}
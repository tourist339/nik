<?php


class newlogin
{
    private static $payload=null;

    public static function isPayloadNull(){
        return self::$payload==null;
    }
    public static function setPayload($pay){
        self::$payload=$pay;
    }

    /**
     * @return mixed
     */
    public static function getPayload()
    {
        return self::$payload;
    }

}
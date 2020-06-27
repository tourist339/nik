<?php


class Application
{
//    Default controller,method and prams
    protected $controller="homeController";
    protected $method="index";
    protected $prams=[];

    public function __construct()
    {
        $this->set_controller();
        if(file_exists(CONTROLLER.$this->controller.".php")){
            $currC=new $this->controller;
            if(method_exists($currC,$this->method)){
                call_user_func_array([$currC,$this->method],$this->prams);
            }
        }else{
            new e404Controller();

        }
    }
    function set_controller(){
        $url=trim($_SERVER['REQUEST_URI'],'/');
        $url=explode('/',$url);

        $this->controller=!empty($url[0])?$url[0]."Controller":$this->controller;
        $this->method=isset($url[1])?$url[1]:$this->method;
        unset($url[0],$url[1]);
        $this->prams=$url;
    }

}

?>
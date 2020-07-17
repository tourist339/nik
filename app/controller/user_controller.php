<?php


class user_controller extends Controller
{
    public function index(){

        $currView= $this->createView("user/index", ["title" => "User",
            "scripts" => [MAIN_SCRIPTS,"https://kit.fontawesome.com/43ac8e0dfc.js"],
            "stylesheets" => [MAIN_CSS,"user/main.css"]     ]
        );
        $currView->render(true,false);
    }

    public function dashboard(){
        $model=new user_model(DB_NAME);
        $p=["first_name","last_name","address","city","state","phone_num","pic"];
        session_start();
        $email=$_SESSION["email"];
        $login_type=$_SESSION["login_type"];
        $userInfo=$model->getUserData($p,$email,$login_type);
        $currView= $this->createView("user/dashboard",["data"=>$userInfo]);
        $currView->render(false,false);
    }
    public function events(){

    }
    public function properties(){
        $currView= $this->createView("user/properties");
        $currView->render(false,false);
    }
}
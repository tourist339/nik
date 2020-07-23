<?php


class user_controller extends Controller
{
    public function index(){

        $currView= $this->createView("user/index", ["title" => "User",
            "scripts" => [MAIN_SCRIPTS],
            "stylesheets" => [MAIN_CSS,"user/main.css","single-listing.css","listprops.css"]
            ]
        );
        $currView->render(true,false);
    }

    public function dashboard(){
        $model=new user_model();
        $p=["first_name","last_name","address","city","state","phone_num","pic"];
        session_start();
        $id=$_SESSION["id"];
        $userInfo=$model->getUserDataByID($p,$id);
        $currView= $this->createView("user/dashboard",["data"=>$userInfo]);
        $currView->render(false,false);
        $model->closeDb();
    }
    public function events(){

    }
    public function properties(){
        $usermodel=new user_model();
        $propmodel=new prop_model();
        session_start();
        $id=$_SESSION["id"];
        $props_array=$usermodel->getUserDataByID(["properties"],$id);
        $props=[];
        if($props_array[0]["properties"]!=null) {
            $props_id = explode(",", $props_array[0]["properties"]);
            var_dump($props_id);
            foreach ($props_id as $p_id) {
                $propdata = $propmodel->getData(["id", "title", "description", "rent", "address"], ["id" => $p_id]);
                array_push($props, $propdata);
            }
        }
        $currView= $this->createView("user/properties",["data"=>$props]);
        $currView->render(false,false);
    }
}
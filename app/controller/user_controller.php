<?php


class user_controller extends Controller
{
    public function index(){

        $currView= $this->createView("user/index", ["title" => "User",
            "scripts" => [MAIN_SCRIPTS,"loadprops.js"],
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
        $propmodel=new prop_model();  //for getting approved props
        $adminmodel=new admin_model(); //for getting unapproved props
        session_start();
        $id=$_SESSION["id"];
        $props_array=$usermodel->getUserDataByID(["approved_properties","unapproved_properties"],$id);
        $approved_props=[];
        $unapproved_props=[];
        if($props_array[0]["approved_properties"]!=null || $props_array[0]["unapproved_properties"]!=null) {
            $approved_props_ids = explode(",", $props_array[0]["approved_properties"]);
            $unapproved_props_ids = explode(",", $props_array[0]["unapproved_properties"]);
            var_dump($approved_props_ids);
            foreach ($approved_props_ids as $p_id) {
                $propdata = $propmodel->getSingleProp(["id","title", "description", "rent", "address","city","images"], ["id" => $p_id]);
                array_push($approved_props, $propdata[0]);
            }
            foreach ($unapproved_props_ids as $p_id) {
                $propdata = $adminmodel->getPropertyById($p_id,["id","title", "description", "rent", "address","city","images"]);
                array_push($unapproved_props, $propdata[0]);
            }
        }
        $currView= $this->createView("user/properties",["approved_props"=>$approved_props
            ,"unapproved_props"=>$unapproved_props]);
        $currView->render(false,false);
    }
}
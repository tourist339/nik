<?php


class admin_controller extends Controller
{

    public function __construct()
    {
        $this->model=new admin_model();
    }

    public function login()
    {
        session_start();
        if(isset($_SESSION["admin"])){
            header("Location:/".ADMIN_URL."/panel");
        }else {
            $cview = $this->createView('/'.ADMIN_URL.'/login');
            $cview->render(false, false);
        }
    }
    public function loginUser(){
        if(isset($_POST["admin_username"]) && isset($_POST["admin_password"])){
            if($this->model->checkCredentials($_POST["admin_username"],$_POST["admin_password"])){
                session_start();
                session_regenerate_id(true);
                $_SESSION["admin"]=$_POST["admin_username"];
                header("Location: /".ADMIN_URL."/panel");
            }else{
                header("Location: /".ADMIN_URL."/login");
            }
            $this->model->closeDb();
        }
    }

    public function panel(){
        $this->checkAdminLoggedIn();
        $cview = $this->createView('/admin/panel'
        );
        $cview->render(false, false);

    }
    public function logout(){
        session_start();
        session_unset();
        session_destroy();
        header("Location: /admin/login");
    }

    public function getuser(){
        $usermodel=new user_model();

    }




    public function showUnapprovedProps(){
        $cityname="";
        $this->checkAdminLoggedIn();

        if(isset($_GET["loc_name"])){
                $cityname=$_GET["loc_name"];
            }
            $props=$this->model->getUnapproveProps($cityname,["id","ownerid","title","city","address","state","lyfly"]);
            if(empty($props)){
                echo "No property found";
            }else{
                $cview = $this->createView('/'.ADMIN_URL.'/listprops',array(
                    "title"=>"Properties",
                    "scripts"=>["jquery-3.5.1.js"],
                    "stylesheets" => ["admin/listprops.css"],
                    "redirect_city"=>$cityname,
                    "data"=>$props));
                $cview->render(HEADER_SCRIPTS_AND_CSS,FOOTER_NONE);
            }
            $this->model->closeDb();;

    }


    public function listSingleProp(){
        $this->checkAdminLoggedIn();
        if(isset($_GET["prop_id"])){
            $data=$this->model->getPropertyById($_GET["prop_id"]);
            $usermodel=new user_model($data[0]["ownerid"]);
            $ownerdata=$usermodel->getUserDataByID(["first_name","last_name","email","phone_num"]);

            $this->createView('prop/singleprop', ["title" => "LyfLy",
                    "scripts" => [MAIN_SCRIPTS,"admin/singleprop.js"],
                    "stylesheets" => [MAIN_CSS,"homepage.css","prop.css"],
                    "data" => $data,
                    "admin"=>true,
                    "redirect_city"=>$_GET["redirect_city"],
                    "ownerdata"=>$ownerdata]
            )->render(HEADER_SCRIPTS_AND_CSS,FOOTER_NONE);
            $this->model->closeDb();
            $usermodel->closeDb();
        }
    }

    public function approveProp(){
        $this->checkAdminLoggedIn();
        if(isset($_POST["prop_id"])){
            $prop_id=$_POST["prop_id"];
            $admin=$_SESSION["admin"];
            $data=$this->model->getPropertyById($prop_id)[0];
            $keys = array_keys($data);
            $new_data=[];
            foreach ($keys as $key){
                $new_data[":".$key]=$data[$key];
            }
            $new_data[":admin"]=$admin;
            $ownerid=$new_data[":ownerid"];
            //delete the id key from data array coz we dont need to post the unapproved prop's id
            $unapprovedprop_id=$new_data[":id"];
            unset($new_data[":id"]);


            $usermodel=new user_model($ownerid);
            $approved_prop_id=$this->model->addPropToMainTable($new_data);
            if($approved_prop_id != DB_ERROR_CODE){
                //remove the property from unpproved_props column in the owner's row
                $usermodel->removeUnapprovedProp($unapprovedprop_id);

                //remove the already approved property from temp_properties table
                if($this->model->deleteTempRow($unapprovedprop_id) !=DB_ERROR_CODE){
                    //add the approved property id to the approved_properties column in the owner's row
                    $usermodel->updateApprovedProperties($approved_prop_id);
                    header("Location: /".ADMIN_URL."/showUnapprovedProps?loc_name=".$_POST["redirect_city"]);
                }else{
                    echo "Something went wrong while deleting temperory row . Contact Admin";
                }
            }else{
                echo "Something went wrong while adding property to main table. Contact ADmin";

            }

            $this->model->closeDb();
        }

    }
    private function checkAdminLoggedIn(){
        session_start();
        if(!isset($_SESSION["admin"])){
            header("Location: /".ADMIN_URL."/login");
        }
    }
}
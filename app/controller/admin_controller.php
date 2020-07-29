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
            $cview = $this->createView('/admin/login');
            $cview->render(false, false);
        }
    }
    public function loginUser(){
        if(isset($_POST["admin_username"]) && isset($_POST["admin_password"])){
            if($this->model->checkCredentials($_POST["admin_username"],$_POST["admin_password"])){
                session_start();
                session_regenerate_id(true);
                $_SESSION["admin"]=$_POST["admin_username"];
                header("Location: /admin/panel");
            }else{
                header("Location: /admin/login");
            }
            $this->model->closeDb();
        }
    }

    public function panel(){
        session_start();
        if(isset($_SESSION["admin"])) {
            $cview = $this->createView('/admin/panel'
            );
            $cview->render(false, false);
        }else{
            header("Location: /admin/login");
        }
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


    public function add_property(){
        //handling ajax request from setup.phtml page for creating property\
        if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest') {

            if(!empty($_FILES["images"]["name"][0])){
                if(SETUP_DEBUG_MODE) {
                    if (count($_FILES["images"]["name"]) < 5) {
                        new e404_controller("Must upload atleast 5 images");
                    }
                }
            }else{
                new e404_controller("No image uploaded");
            }

            $keys = [":pType", ":pSharingType", ":pNoGuests", ":pNoBeds", ":pNoBathrooms", ":pBathroomShared", ":pKitchenAvailable", ":pTitle"
                , ":pDesc", ":pAddress", ":pApt", ":pCity", ":pState", ":pRent", ":amenities"];
            $required = ["pNoGuests", "pNoBathrooms", "pBathroomShared", "pAddress", "pCity", "pState", "pRent"];
            if (SETUP_DEBUG_MODE) {
                foreach ($required as $req) {
                    if (!isset($_POST[$req]))
                        new e404_controller("Invalid Data");
                }
            }
            $values = array();
            if (isset($_POST)) {
                foreach ($keys as $key) {
                    if (!isset($_POST[ltrim($key, ":")]) || empty($_POST[ltrim($key, ":")])) {
                        $val = null;
                    } else {
                        $posted_data = $_POST[ltrim($key, ":")];
                        if (is_array($posted_data)) {
                            for ($i = 0; $i < count($posted_data); $i++) {
                                $posted_data[$i] = $this->removeSPandTrim($posted_data[$i]);
                            }
                            $val = implode(",", $posted_data);
                        } else {
                            $val = $this->removeSPandTrim($posted_data);
                        }
                    }
                    array_push($values, $val);;
                }
                session_start();

                if (isset($_SESSION["id"])) {
                    $imgs=$this->upload_images($_FILES["images"],$_SESSION["id"]);
                    $imgs_string=implode(",",$imgs);
                    // var_dump($imgs_string);
                    array_push($keys, ":images");
                    array_push($values, $imgs_string);

                    array_push($keys, ":ownerid");
                    array_push($values, $_SESSION["id"]);
                    $data = array_combine($keys, $values);
                    print_r($data);
                    $adminmodel = new host_model();
                    $prop_id=$adminmodel->createPropRow($data);
                    $usermodel=new user_model();
                    $usermodel->updateProperty($prop_id,$_SESSION["id"]);
                    $usermodel->closeDb();
                    $adminmodel->closeDb();
                } else {
                    new e404_controller("Not logged in");
                }

            }
        }



    }

    private function upload_images($data,$id)
    {
        if(!is_dir(UPLOAD.$id)) {
            mkdir(UPLOAD . $id);
        }

        $imgs=[];
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp', 'pdf', 'doc', 'ppt'); // valid extensions

        $no_of_images = count($data["name"]);

        for ($i = 0; $i < $no_of_images; $i++) {
            $storepath=$id.DIRECTORY_SEPARATOR;
            $path=UPLOAD.$storepath;

            $name = $data["name"][$i];
            $tmp_name = $data["tmp_name"][$i];
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $final_image = rand(1000,1000000).$name;

            if (in_array($ext, $valid_extensions)) {
                $path.=strtolower($final_image);
                $storepath.=strtolower($final_image);
                if (move_uploaded_file($tmp_name, $path)) {
                    array_push($imgs,$storepath);
                }
            }
        }
        return $imgs;
    }

    public function showUnapprovedProps(){
        session_start();
        $cityname="";
        if(isset($_SESSION["admin"])){
            if(isset($_POST["loc_name"])){
                $cityname=$_POST["loc_name"];
            }
            $props=$this->model->getUnapproveProps($cityname);
            var_dump($props);
            $this->model->closeDb();;
        }
    }

}
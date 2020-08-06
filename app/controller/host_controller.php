<?php


class host_controller extends Controller
{
    public function index()
    {
        $cView = $this->createView('/host/overview', ["title" => "Hosting OverView",
                "scripts" => [MAIN_SCRIPTS,"homepage/homepage.js"],
                "stylesheets" => [MAIN_CSS,"host.css"],
                "navbar" => MAIN_NAVBAR]

        );
        $cView->render();
    }

    public function setup(){
        session_start();
        if(isset($_SESSION["id"])) {
            $cView = $this->createView('/host/setup', ["title" => "Setup",
                    "scripts" => [MAIN_SCRIPTS],
                    "stylesheets" => [MAIN_CSS, "host.css", "setup.css"],
                    "navbar" => MAIN_NAVBAR]
            );
            $cView->render(true, false);
        }else{
            echo 'Not logged in';

        }


    }

    public function add_temp_property(){
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
                , ":pDesc", ":pAddress", ":pApt", ":pCity", ":pState", ":pRent", ":amenities",":pGender",":hRules"];

            //required inputs that have to be submitted via form
            $required = ["pNoGuests", "pNoBathrooms", "pAddress", "pCity", "pState","pGender", "pRent"];
            if (SETUP_DEBUG_MODE) {
                foreach ($required as $req) {
                    if (!isset($_POST[$req]) || empty($_POST[$req]) )
                        new e404_controller("Invalid Data ".$req);
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

                    array_push($keys, ":images");
                    array_push($values, $imgs_string);

                    array_push($keys, ":ownerid");
                    array_push($values, $_SESSION["id"]);
                    $data = array_combine($keys, $values);
                    print_r($data);
                    $adminmodel = new admin_model();
                    $prop_id=$adminmodel->createPropRow($data);
                    if($prop_id!=DB_ERROR_CODE){
                        $usermodel=new user_model();
                        if($usermodel->updateUnApprovedProperties($prop_id,$_SESSION["id"])!=DB_ERROR_CODE){
                            echo "true";
                        }else{
                            echo "false";
                        }
                        $usermodel->closeDb();
                    }

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
}
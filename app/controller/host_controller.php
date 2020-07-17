<?php


class host_controller extends Controller
{
    public function index()
    {
        $cView = $this->createView('/host/overview', ["title" => "Hosting OverView",
                "scripts" => [MAIN_SCRIPTS],
                "stylesheets" => [MAIN_CSS,"host.css"],
                "navbar" => MAIN_NAVBAR]

        );
        $cView->render();
    }

    public function setup(){
        session_start();
        if(isset($_SESSION["id"])) {
            $cView = $this->createView('/host/setup', ["title" => "Hosting OverView",
                    "scripts" => [MAIN_SCRIPTS],
                    "stylesheets" => [MAIN_CSS, "host.css", "setup.css"],
                    "navbar" => MAIN_NAVBAR]
            );
            $cView->render(true, false);
        }else{
            echo 'Not logged in';

        }


    }

    public function add_property(){
        //handling ajax request from setup.phtml page for creating property

        if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest') {


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
                    array_push($keys, ":ownerid");
                    array_push($values, $_SESSION["id"]);
                    $data = array_combine($keys, $values);
                    print_r($data);
                    $hostmodel = new host_model();
                    $prop_id=$hostmodel->createPropRow($data);
                    $usermodel=new user_model();
                    $usermodel->updateProperty($prop_id,$_SESSION["id"]);
                    $usermodel->closeDb();
                    $hostmodel->closeDb();
                } else {
                    new e404_controller("Not logged in");
                }

            }
        }

    }
}
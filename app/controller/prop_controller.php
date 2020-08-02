<?php


class prop_controller extends Controller
{
    public function __construct()
    {
        $this->model = new prop_model("system_d");
    }

    public function l($location)
    {

            $location=$this->removeSPandTrim($location);
            $data = $this->model->getData(["id","title", "description", "rent", "address"], ["location"=>$location]);
            if ($data == null) {
                new e404_controller("No Property Found in this city");
            } else {
                $this->createView('prop/listprops', ["title" => "LyfLy",
                                                            "scripts" => [MAIN_SCRIPTS],
                                                            "stylesheets" => [MAIN_CSS,"homepage.css","single-listing.css","listprops.css"],
                                                            "navbar" => MAIN_NAVBAR,
                                                            "data" => $data]
                                    )->render();
            }
            $this->model->closeDb();

    }


    /**
     * @param $propname TITLE of the property to opened
     * @param $propid ID of the property to be opened
     * Displays the specfic property by getting all the data about it from prop model
     * and creates the view prop/singleprop
     */
    public function v($propname, $propid)
    {
        $propname=str_replace('-',' ',$propname);
        $propname=$this->removeSPandTrim($propname);
        $propid=$this->removeSPandTrim($propid);
        $usermodel=new user_model();

        $data = $this->model->getData([], ["title"=>$propname, "id"=>$propid]);

        //true if prop is wishlisted by user false if not wishlisted or no user is logged in
        $inWishlist="false";

        session_start();
        if(isset($_SESSION["id"])) {
            if($usermodel->checkPropInWishlist($propid,$_SESSION["id"])){
                $inWishlist="true";
            }else{
                $inWishlist="false";

            }
        }
        $ownerdata=$usermodel->getUserDataByID(["first_name","last_name","email","phone_num"],$data[0]["ownerid"]);

        if ($data == null) {
            new e404_controller();
        } else {
            $this->createView('prop/singleprop', ["title" => "LyfLy",
                    "scripts" => [MAIN_SCRIPTS],
                    "stylesheets" => [MAIN_CSS,"homepage.css","prop.css"],
                    "navbar" => MAIN_NAVBAR,
                    "data" => $data,
                    "wishlisted"=>$inWishlist,
                    "ownerdata"=>$ownerdata]
            )->render();
        }
            $this->model->closeDb();
            $usermodel->closeDb();
    }
    public function add_to_wishlist(){
        if(isset($_POST["prop_id"])){
            session_start();
            if(isset($_SESSION["id"])){
                $um=new user_model();
                $um->updateWishlist($_POST["prop_id"],$_SESSION["id"]);
                $um->closeDb();
            }
        }
    }
    public function remove_from_wishlist(){
        if(isset($_POST["prop_id"])){
            $this->checkLoggedIN();
                $um=new user_model();
                $um->removePropFromWishlist($_POST["prop_id"],$_SESSION["id"]);
                $um->closeDb();

        }
    }

    /*
     * More to be worked on
     */
    public function booking(){
       // $this->checkLoggedIN();
        $to="dreamtips3390@gmail.com";
        $subject="New booking request";
//        session_start();
//        $headers = "From: " . strip_tags($_SESSION['email']) . "\r\n";
//        $headers .= "Reply-To: ". strip_tags($_SESSION['email']) . "\r\n";
//        $headers .= "Return-Path: strip_tags".($_SESSION['email'])."\r\n";

        $headers .= "Organization: LYFLY\r\n";

        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        mail($to,$subject,"<html>Hey buddy</html>",$headers);
    }
}
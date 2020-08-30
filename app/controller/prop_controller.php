<?php


class prop_controller extends Controller
{
    private $prop_filters;
    public function __construct()
    {
        $this->model = new prop_model("system_d");
        $this->prop_filters=null;
    }

    /**
     * Creates view from prop/listprops.phtml and gives the data of all the matched properties as
     * well as other required information
     * @param $location name of city or state from where the properties are to be listed
     * @param string $search query string that matches the title of the properties using (SQL LIKE)
     */
    public function l($location,$search="")
    {


        $location=$this->removeSPandTrim($location);
        $search=str_replace('-',' ',$search);
        $search=$this->removeSPandTrim($search);

        //check if filters are set then get the data from applyFilters function
        $data=$this->applyFilters($location,$search);
        $filters=null;
        //if $data is null that means filters are not set , so get data from the model
        if($data==null){
            $data = $this->model->getAllProps(["id","title", "description", "rent", "address","city","images"], ["location"=>$location,"search"=>$search]);
        }else{
            //this means filters are set so get the filters array from the instance variable
            // set by applyFilters function
            $filters=$this->prop_filters;
        }
        $usermodel=new user_model();
        session_start();
        if(isset($_SESSION["id"])) {
            foreach ($data as &$prop){
                $propid=$prop["id"];
                if($usermodel->checkPropInWishlist($propid,$_SESSION["id"])){
                    $prop["wishlisted"]="true";
                }else{
                    $prop["wishlisted"]="false";

                }
            }

        }
        if ($data == null) {
            new e404_controller("No Property Found in this city");
        } else {
            $this->createView('prop/listprops', ["title" => "LyfLy",
                                                        "scripts" => [MAIN_SCRIPTS,"listprops.js","imageslider.js","jquery-ui.min.js"],
                                                        "stylesheets" => [MAIN_CSS,"homepage.css","single-listing.css","listprops.css","jquery-ui.min.css"],
                                                        "navbar" => MAIN_NAVBAR,
                                                        "location"=>ucfirst($location),
                                                        "search"=>$search,
                                                        "filters"=>$filters,
                                                        "data" => $data]
                                )->render();
        }
        $this->model->closeDb();
        $usermodel->closeDb();

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
        $inWishlist="false";

        $data = $this->model->getSingleProp([], ["title"=>$propname, "id"=>$propid]);

        //true if prop is wishlisted by user false if not wishlisted or no user is logged in


        if ($data == null) {
            new e404_controller("PROPERTY NOT FOUND");
        } else {
            session_start();
            if(isset($_SESSION["id"])) {
                if($usermodel->checkPropInWishlist($propid,$_SESSION["id"])){
                    $inWishlist="true";
                }else{
                    $inWishlist="false";

                }
            }
            $ownerdata=$usermodel->getUserDataByID(["first_name","last_name","email","phone_num"],$data[0]["ownerid"]);

            $this->createView('prop/singleprop', ["title" => "LyfLy",
                    "scripts" => [MAIN_SCRIPTS,"singleprop.js"],
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

    private function applyFilters($location,$search=""){
        if(isset($_GET)){
            $filters=["minPrice","maxPrice","gender","pType"];
            $filterToApply=[];
            foreach ($filters as $filter) {
                if (isset($_GET[$filter])) {
                    $filterToApply[":" . $filter] = $this->removeSPandTrim($_GET[$filter]);
                }
            }
            $this->prop_filters=$filterToApply;
                    $data = $this->model->getAllProps(
                        ["id","title", "description", "rent", "address","city","images"],
                        ["location"=>$location,"search"=>$search],$filterToApply);
                    if ($data == null) {
                        return null;
                    }else{
                        return $data;
                    }
        }else{
            return null;
        }

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
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
        $location=ucfirst(strtolower($location));
        $search=str_replace('-',' ',$search);
        $search=$this->removeSPandTrim($search);

        //get the filters from applyFilters function
        $filters=$this->applyFilters($location,$search);

        //if $filters are null that means filters are not set , so get data from the model
        if($filters==null){
            $data = $this->model->getAllProps(["id","title", "description", "rent", "address","city","images"], ["location"=>$location,"search"=>$search]);
        }else{
            //add $filters parameter to getAllProps function
            $data = $this->model->getAllProps(
                ["id","title", "description", "rent", "address","city","images"],
                ["location"=>$location,"search"=>$search],$filters);
        }

        if($data!=null){
            $usermodel=new user_model();
            $citymodel=new city_model();
            $rents=$citymodel->getMinMaxRent($location);
            $minrent=$rents["min_rent"];
            $maxrent=$rents["max_rent"];
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
            $usermodel->closeDb();
            $citymodel->closeDb();

        }

        $this->createView('prop/listprops', ["title" => "LyfLy",
                                                    "scripts" => [MAIN_SCRIPTS,"listprops.js","imageslider.js","jquery-ui.min.js"],
                                                    "stylesheets" => [MAIN_CSS,"homepage.css","single-listing.css","listprops.css","jquery-ui.min.css"],
                                                    "navbar" => MAIN_NAVBAR,
                                                    "location"=>$location,
                                                    "search"=>$search,
                                                    "minrent"=>$minrent,
                                                    "maxrent"=>$maxrent,
                                                    "filters"=>$filters,
                                                    "data" => $data]
                            )->render();

        $this->model->closeDb();

    }





    /**
     * Displays the specfic property by getting all the data about it from prop model
     * and creates the view prop/singleprop
     * @param $propname TITLE of the property to opened
     * @param $propid ID of the property to be opened
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


    /**
     * @param $location
     * @param string $search
     * @return array containing all the filters || null
     */
    private function applyFilters($location,$search=""){
        if(isset($_GET)){
            $filters=["minPrice","maxPrice","gender","pType","bedrooms","bathrooms",
                "kitchen","lyfly","amenities","rules"];
            $amenities=["wifi","laundry","breakfast","dinner","tv","ac","lunch"];
            $houserules=["party","smoking","petfriendly"];
            $filterToApply=[];
            foreach ($filters as $filter) {
                if (isset($_GET[$filter])) {
                    //if filter values are in form of an array for ex in case
                    // of amenities and houserules
                    if(is_array($_GET[$filter])) {
                        $filterToApply[":" . $filter]="%";
                        foreach ($_GET[$filter] as $f){

                            $filterToApply[":" . $filter].=$this->removeSPandTrim($f);

                            $filterToApply[":" . $filter].="%";
                        }
                    }else{
                        $filterToApply[":" . $filter] = $this->removeSPandTrim($_GET[$filter]);

                    }
                }
            }
            return $filterToApply;

        }else{
            return null;
        }

    }

    /**
     *
     */
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

    /**
     *
     */
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
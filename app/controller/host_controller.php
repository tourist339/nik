<?php


class host_controller extends Controller
{
    private $keys = [":proptype", ":sharingtype", ":guests", ":bedrooms", ":bathrooms",
    ":kitchen", ":title", ":description", ":address", ":aptno", ":city", ":state", ":rent",
    ":amenities",":gender",":houseRules",":agreementType",":lyfly"];
    public function index()
    {

        $cView = $this->createView('/host/overview', ["title" => "Hosting OverView",
                "scripts" => [MAIN_SCRIPTS,"homepage/homepage.js"],
                "stylesheets" => [MAIN_CSS,"host_overview.css"],
                "navbar" => MAIN_NAVBAR]
        );
        $cView->render();
    }

    public function hasUnfinishedProperties(){
        Session::startSession();
        if(Session::isLoggedIn()){
            $usermodel=new user_model();
            if($usermodel->hasUnfinishedProperty(Session::getUserId()))
                header("Location:/host/unfinished");
            else
                header("Location:/host/setup");
        }
    }

    public function unfinished(){
        if(Session::isLoggedIn()) {
            $usermodel = new user_model();
            Session::removeOpenedProperty();
            $unfinished_props=$usermodel->getUnfinishedProperties(Session::getUserId());
            $indexes=$usermodel->getIndexesUnfinishedProperty(Session::getUserId());
            $cView = $this->createView('/host/unfinished', ["title" => "Unfinished Properties",
                    "scripts" => [MAIN_SCRIPTS, "homepage/homepage.js"],
                    "stylesheets" => [MAIN_CSS, "host/unfinished.css"],
                    "properties"=>$unfinished_props,
                    "indexes"=>$indexes,
                    "navbar" => MAIN_NAVBAR]
            );
            $cView->render();
        }else{
            header("Location: /host");
        }
    }

    public function setup($index=false){
        Session::startSession();
        var_dump($_SESSION);
        if(Session::isLoggedIn()) {
            $user_id=Session::getUserId();

            $usermodel=new user_model();
            $propmodel=new prop_model();


            $prop=null;

            if($index!==false ){

                if (is_numeric($index)) {
                    $prop_id = $usermodel->getUnfinishedPropertyUsingIndex($index, $user_id);
                    if ($prop_id !== false) {
                        $prop = $propmodel->getPropertyById($prop_id, [], Table::UNFINISHED_PROPS);
                    }
                }
            }else if(($index=Session::getOpenedProperty())!==false){
                    $prop_id = $usermodel->getUnfinishedPropertyUsingIndex($index, $user_id);
                    if ($prop_id !== false) {
                        $prop = $propmodel->getPropertyById($prop_id, [], Table::UNFINISHED_PROPS);
                    }
            }
            $cView = $this->createView('/host/setup', ["title" => "Setup",
                    "scripts" => [MAIN_SCRIPTS],
                    "stylesheets" => [MAIN_CSS, "host_overview.css", "setup.css"],
                    "navbar" => MAIN_NAVBAR,
                    "current_prop"=>$prop,
                    "index"=>$index]
            );
            $cView->render(true, false);
        }else{
            echo 'Not logged in';

        }

    }

    private function getPropData($post_array,$userid){
        $values = array();

        foreach ($this->keys as $key) {
            if (!isset($post_array[ltrim($key, ":")]) || empty($post_array[ltrim($key, ":")]) ) {
                $val = null;
            } else {
                $posted_data = $post_array[ltrim($key, ":")];
                if (is_array($posted_data)) {
                    for ($i = 0; $i < count($posted_data); $i++) {
                        $posted_data[$i] = $this->removeSPandTrim($posted_data[$i]);
                    }
                    $val = implode(",", $posted_data);
                } else {
                    $val = $this->removeSPandTrim($posted_data);
                }
            }
            array_push($values, $val);
        }



        array_push($this->keys, ":ownerid");
        array_push($values, $userid);
        $data = array_combine($this->keys, $values);
        //only uppercase the first letter of city and state
        $data[":city"]=ucfirst(strtolower($data[":city"]));
        $data[":state"]=ucfirst(strtolower($data[":state"]));
        return $data;
    }

    public function store_unfinished($prop_index=null){
        if(Session::isLoggedIn()){
            $userid=Session::getUserId();

            var_dump("prop index".$prop_index);


            if (isset($_POST)) {

                $data=$this->getPropData($_POST,$userid);

                $hostmodel = new host_model();
                $usermodel=new user_model();

                if ($prop_index!=null){
                    $prop_id=$usermodel->getUnfinishedPropertyUsingIndex($prop_index,$userid);
                    if ($prop_id!=-1)
                        $hostmodel->updateUnfinishedPropRow($prop_id,$data);
                    else
                        new e404_controller("Not a valid property");

                }else if(($prop_index=Session::getOpenedProperty())!==false){
                    $prop_id=$usermodel->getUnfinishedPropertyUsingIndex($prop_index,$userid);
                    if ($prop_id!=-1)
                        $hostmodel->updateUnfinishedPropRow($prop_id,$data);
                    else
                        new e404_controller("Not a valid property");
                } else{
                    $prop_id = $hostmodel->createUnfinishedPropRow($data);

                    if ($prop_id != DB_ERROR_CODE) {
                        var_dump("prop_id" . $prop_id);
                        $updated_prop_index=$usermodel->updateUnfinishedProperties($prop_id, $userid);


                        Session::storeOpenedProperty($updated_prop_index-1);

                        $usermodel->closeDb();
                    }
                }

                $hostmodel->closeDb();
            } else {
                new e404_controller("Not logged in");
            }

            }
        }


    public function delete_unfinished($prop_index=null,$redirect=true){
        if ($prop_index!==null&& Session::isLoggedIn()&&is_numeric($prop_index)){
            $user_model = new user_model();
            $propid =$user_model->removeUnfinishedProperty($prop_index,Session::getUserId());
            if ($propid!= DB_ERROR_CODE ){
                var_dump($propid);
                $prop_model=new prop_model();
                $prop_model->deleteUnfinishedProperty($propid);
                if ($redirect) {
                    if ($user_model->hasUnfinishedProperty(Session::getUserId()))
                        header("Location:/host/unfinished");
                    else
                        header("Location:/host");
                }
            }else{
                echo $propid;
            }
        }else{
            new e404_controller("Wrong URL");
        }
    }



    public function add_temp_property($prop_index=null){
        //handling ajax request from setup.phtml page for creating property\
        if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest') {

            if (Session::isLoggedIn() && isset($_POST)) {
                $userid=Session::getUserId();
                $lyfly=false;

                //all the possible inputs to be submitted from setup.phtml


                //required inputs that have to be submitted via form
                $required = ["pNoGuests", "pNoBathrooms", "pAddress", "pCity", "pState","pGender"];

                //check if not in dev stage
                if (SETUP_DEBUG_MODE) {

                    if(isset($_POST["pLyfly"]) && !strlen($_POST["pLyfly"])==0){

                        //if property is not managed by lyfly add rent , agreement type and images to required check
                        // change $lyfly to true otherwise
                        if($_POST["pLyfly"]=="0"){
                            array_push($required,"pAgreement");
                            array_push($required,"pRent");

                            //check so that image number is more than or equal to 5
                            if(!empty($_FILES["images"]["name"][0])){
                                if(SETUP_DEBUG_MODE) {
                                    if (count($_FILES["images"]["name"]) < 5) {
                                        new e404_controller("Must upload atleast 5 images");
                                    }
                                }
                            }else{
                                new e404_controller("No image uploaded");
                            }
                        }else{
                            $lyfly=true;
                        }
                    }else{
                        echo empty($_POST["pLyfly"]);
                        new e404_controller("<br>Invalid Data lyfly");
                    }
                    foreach ($required as $req) {
                        if (!isset($_POST[$req]) || empty($_POST[$req]) )
                            new e404_controller("Invalid Data ".$req);
                    }
                }




                $data = $this->getPropData($_POST,$userid);

                $imgs_string="";

                //if prop is not managed by lyfly only then upload user images
                if(!$lyfly) {
                    $imgs = $this->upload_images($_FILES["images"], $_SESSION["id"]);
                    $imgs_string = implode(",", $imgs);
                }
                $data[":ownerid"]=$userid;
                $data[":images"]=$imgs_string;




                $adminmodel = new admin_model();
                $prop_id=$adminmodel->createPropRow($data);
                if($prop_id!=DB_ERROR_CODE){
                    $usermodel=new user_model();
                    if($usermodel->updateUnApprovedProperties($prop_id,$userid)!=DB_ERROR_CODE){
                        echo "true";
                    }else{
                        echo "false";
                    }
                    Session::removeOpenedProperty();
                    if ($prop_index!==null){
                        if (is_numeric($prop_index)){
                            if($usermodel->removeUnfinishedProperty($prop_index,$userid)!=DB_ERROR_CODE)
                                echo "unfinished prop deleted";
                            else
                                echo "unfinished prop not deleted";
                        }
                    }else{
                        if($this->delete_unfinished($prop_index,false)!=DB_ERROR_CODE)
                            echo "unfinished prop deleted";
                        else
                            echo "unfinished prop not deleted";
                    }
                    $usermodel->closeDb();
                }

                $adminmodel->closeDb();
            } else {
                new e404_controller("Not logged in");
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
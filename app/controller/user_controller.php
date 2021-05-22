<?php


class user_controller extends Controller
{
    public function index()
    {
        $this->checkLoggedIN();
        $model=new user_model(Session::getUserId());

        $p=["first_name","last_name","address","city","state","phone_num","pic"];

        $userInfo=$model->getUserDataByID($p);
        $currView= $this->createView("user/dashboard",
            ["title" => "Dashboard",
            "scripts" => [MAIN_SCRIPTS],
            "navbar"=>MAIN_NAVBAR,
            "data"=>$userInfo,
            "stylesheets" => [MAIN_CSS,"user/main.css","user/dashboard.css"]
            ]
        );
        $currView->render(true,false);
        $model->closeDb();
    }

    /**
     * Just call the index function on dashboard , as they are supposed to load the same content
     */
    public function dashboard(){
        $this->index();
    }


    /**
     *
     */
    public function properties(){

        $this->checkLoggedIN();
        $usermodel=new user_model(Session::getUserId());
        $propmodel=new prop_model();  //for getting approved props
        $adminmodel=new admin_model(); //for getting unapproved props
        $props_array=$usermodel->getUserDataByID(["approved_properties","unapproved_properties"]);
        $approved_props=[];
        $unapproved_props=[];
        if($props_array[0]["approved_properties"]!=null ) {
            $approved_props_ids = explode(",", $props_array[0]["approved_properties"]);
            foreach ($approved_props_ids as $p_id) {
                $propdata = $propmodel->getSingleProp(["id","title", "description", "rent", "address","city","images"], ["id" => $p_id]);
                array_push($approved_props, $propdata[0]);
            }

        }
        if( $props_array[0]["unapproved_properties"]!=null){
            $unapproved_props_ids = explode(",", $props_array[0]["unapproved_properties"]);
            foreach ($unapproved_props_ids as $p_id) {
                $propdata = $adminmodel->getPropertyById($p_id,["id","title", "description", "rent", "address","city","images"]);
                array_push($unapproved_props, $propdata[0]);
            }
        }
        $currView= $this->createView("user/properties",
            ["title" => "Properties",
                "scripts" => [MAIN_SCRIPTS],
                "navbar"=>MAIN_NAVBAR,
                "approved_props"=>$approved_props,
                "unapproved_props"=>$unapproved_props,
                "stylesheets" => ["listprops.css","single-listing.css",MAIN_CSS,"user/main.css","user/properties.css"]
            ]
        );
        $currView->render(true,false);


        $usermodel->closeDb();
        $propmodel->closeDb();
        $usermodel->closeDb();

    }
}
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

    public function setup($req_type = "")
    {
        //handling ajax request from setup.phtml page for creating property
        if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest') {
            $keys=[":pType",":pSharingType",":pNoGuests",":pNoBeds",":pNoBathrooms",":pBathroomShared",":pKitchenAvailable",":pTitle"
                    ,":pDesc",":pAddress",":pApt",":pCity",":pState",":pRent",":amenities"];
            $values=array();
            if(isset($_POST)){
                foreach($keys as $key){

                    if(!isset($_POST[ltrim($key,":")]) || empty($_POST[ltrim($key,":")]))
                        $val=null;
                    else
                        $val=$_POST[ltrim($key,":")];
                    array_push($values,$val);

;                }
                $data=array_combine($keys,$values);
                print_r($data);
                $this->model=new host_model("system_d");
                $this->model->createPropRow($data);
                $this->model->closeDb();

            }

        }else {
            $cView = $this->createView('/host/setup', ["title" => "Hosting OverView",
                    "scripts" => [MAIN_SCRIPTS],
                    "stylesheets" => [MAIN_CSS,"host.css","setup.css"],
                    "navbar" => MAIN_NAVBAR]
            );
            $cView->render(true, false);
        }

    }
}
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


    public function v($propname, $id)
    {

        $propname=str_replace('-',' ',$propname);
        $propname=$this->removeSPandTrim($propname);
        $id=$this->removeSPandTrim($id);
        $data = $this->model->getData([], ["title"=>$propname, "id"=>$id]);
        $usermodel=new user_model();
        $ownerdata=$usermodel->getUserDataByID(["first_name","last_name","email","phone_num"],$data[0]["ownerid"]);

        if ($data == null) {
            new e404_controller();
        } else {
            $this->createView('prop/singleprop', ["title" => "LyfLy",
                    "scripts" => [MAIN_SCRIPTS],
                    "stylesheets" => [MAIN_CSS,"homepage.css","prop.css"],
                    "navbar" => MAIN_NAVBAR,
                    "data" => $data,
                "ownerdata"=>$ownerdata]
            )->render();
        }
            $this->model->closeDb();

    }
}
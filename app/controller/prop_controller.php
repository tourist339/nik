<?php


class prop_controller extends Controller
{
    public function __construct()
    {
        $this->model = new prop_model("system_d");
    }

    public function l($location)
    {

            $data = $this->model->getData(["id","title", "description", "rent", "address"], ["location"=>$location]);
            if ($data == null) {
                new e404_controller("No Property Found in this city");
            } else {
                $this->createView('prop/listprops', ["title" => "LyfLy",
                    "scripts" => ["jquery-3.5.1.js"],
                    "stylesheets" => ["homepage.css", "main.css"],
                    "navbar" => "navbar.html",
                    "data" => $data])->render();
            }
            $this->model->closeDb();

    }


    public function v($propname, $id)
    {
        $propname=str_replace('-',' ',$propname);
        $data = $this->model->getData([], ["title"=>$propname, "id"=>$id]);
        if ($data == null) {
            new e404_controller();
        } else {
            $this->createView('prop/singleprop', ["title" => "LyfLy",
                "scripts" => ["jquery-3.5.1.js"],
                "stylesheets" => ["homepage.css", "main.css"],
                "navbar" => "navbar.html"])->render();
        }
            $this->model->closeDb();

    }
}
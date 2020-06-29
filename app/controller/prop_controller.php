<?php


class prop_controller extends Controller
{
    public function index($location="",$propname="",$id="")
    {
        $this->model=new prop_model("system_d");
        if (!(empty($location))&&(empty($propname)&&empty($id))) {
            $data=$this->model->getData(["title","description","rent","address"],$location);
            print_r($data);
            $cView = $this->createView('prop/showprops', ["title" => "LyfLy",
                "scripts" => ["jquery-3.5.1.js"],
                "stylesheets" => ["homepage.css", "main.css"],
                "navbar" => "navbar.html"]);
            $cView->render();
        }elseif(!(empty($location))&&(!empty($propname)&&!empty($id))) {
            $this->model->getData([],$location,true,$propname,$id);

        }else{

            new e404_controller();
        }
        $this->model->closeDb();
    }


}
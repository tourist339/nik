<?php


class host_controller extends Controller
{
    public function overview()
    {
        $cView = $this->createView('/host/overview', ["title" => "Hosting OverView",
            "scripts" => ["jquery-3.5.1.js"],
            "stylesheets" => ["host.css", "main.css"],
            "navbar" => "navbar.html"]);
        $cView->render();
    }

    public function setup($req_type = "")
    {
        if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest') {
            $keys=[""];
            $values=null;
            if(isset($_POST)){

                $this->model=new host_model();
                $this->model->createPropRow();
                $db=$this->model->getDb();
                $db=null;
                $this->model->create();
            }


        }
            $cView = $this->createView('/host/setup', ["title" => "Hosting OverView",
            "scripts" => ["jquery-3.5.1.js"],
            "stylesheets" => ["setup.css", "main.css", "homepage.css"],
            "navbar" => "navbar.html"]);
        $cView->render(true, false);

    }

    public function postProperties()
    {


    }
}
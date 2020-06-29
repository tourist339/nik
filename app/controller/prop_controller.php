<?php


class prop_controller extends Controller
{
    public function index($location="",$propname="",$id="")
    {
        if ($location!="") {

            $cView = $this->createView('prop/showprops', ["title" => "LyfLy",
                "scripts" => ["jquery-3.5.1.js"],
                "stylesheets" => ["homepage.css", "main.css"],
                "navbar" => "navbar.html"]);
            $cView->render();
        }else {
            new e404_controller();
        }
    }


}
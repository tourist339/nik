<?php


class prop_controller extends Controller
{
    public function index(){
        $cView=$this->createView('/prop/index',["title"=>"LyfLy",
            "scripts"=>["jquery-3.5.1.js"],
            "stylesheets"=>["homepage.css","main.css"],
            "navbar"=>"navbar.html"]);
        $cView->render();
    }
}
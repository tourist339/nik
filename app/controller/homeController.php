<?php


class homeController extends Controller
{
    public function __construct()
    {

    }
    public function index(){
        //include title ,scripts with .js extension and stylesheets with .css extension in the data array
        //navbars are in template/navbars dir , create new one or choose any of the pre existing
        $cView=$this->createView('/home/index',["title"=>"LyfLy",
                                                        "scripts"=>["jquery-3.5.1.js"],
                                                        "stylesheets"=>["homepage.css","main.css"],
                                                        "navbar"=>"navbar.html"]);
        $cView->render();

    }
    private function getDefaultData(){

    }
}
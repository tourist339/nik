<?php


class homeController extends Controller
{
    public function __construct()
    {
    }
    public function index(){
        //include title ,scripts with .js extension and stylesheets with .css extension in the data array
        $cView=$this->updateView('/home/index',["title"=>"LyfLy",
                                                        "scripts"=>["jquery-3.5.1.js","navbarinc.js"],
                                                        "stylesheets"=>["homepage.css","main.css"]]);
        $cView->render();

    }
}
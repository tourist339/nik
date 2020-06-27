<?php


class hostController
{
    public function overview(){
        $cView=$this->createView('/host/overview',["title"=>"Hosting OverView",
                                                "scripts"=>["jquery-3.5.1.js"],
                                                "stylesheets"=>["homepage.css","main.css"],
                                                "navbar"=>"navbar.html"]);
        $cView->render();
    }
}
<?php


class prop_controller extends Controller
{
    public function showall($location)
    {
        if (isset($location)) {
            $cView = $this->createView('prop/showprops', ["title" => "LyfLy",
                "scripts" => ["jquery-3.5.1.js"],
                "stylesheets" => ["homepage.css", "main.css"],
                "navbar" => "navbar.html"]);
            $cView->render();
        } else {
            new e404_controller();
        }
    }

    public function showone($location, $propname, $id)
    {

    }
    public function __call($method, $arguments) {
        if($method == 'index') {
            echo "56";
            if(count($arguments) == 1) {
                 call_user_func_array(array($this,'showall'), $arguments);
            }
            else if(count($arguments) == 3) {
                 call_user_func_array(array($this,'showone'), $arguments);
            }
        }
    }
}
<?php


class Controller
{
    private $view;
    public function createView($viewPath,$viewData=[],$view_type="file"){
        $this->view=new View($viewPath,$viewData,$view_type);
        return $this->view;
    }
    public function getView($viewPath,$viewData=[]){
        return $this->view;
    }


}
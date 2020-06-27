<?php


class Controller
{
    private $view;
    public function updateView($viewPath,$viewData=[]){
        $this->view=new View($viewPath,$viewData);
        return $this->view;
    }
    public function getView($viewPath,$viewData=[]){
        return $this->view;
    }
}
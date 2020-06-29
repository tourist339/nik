<?php


class prop_model extends Model
{
    public function __construct($dbname)
    {
        parent::__construct($dbname);

    }

    public function getData($param=[]){
        $db=$this->getDb();
        if(empty($param)){
           // $stmt=$db->prepare("SELECT * FROM ")
        }
    }
}
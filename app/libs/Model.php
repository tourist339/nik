<?php


class Model
{



    public function __construct(){
        $this->db=Database::getDatabase();
    }


    public  function getDb()
    {
        return $this->db;

    }
    public function closeDb()
    {
        $this->db=null;
    }



}
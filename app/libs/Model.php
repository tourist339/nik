<?php


class Model extends Database
{
    private $db;


    public function __construct($dbname=DB_NAME){
        $this->db=new Database($dbname);
        //$this->db
    }


    /**
     * @return Database
     */
    public function getDb()
    {
        return $this->db;
    }
    public function closeDb()
    {
        $this->db=null;
    }



}
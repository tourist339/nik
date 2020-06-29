<?php


class Model
{
private $db;
public function __construct($dbname){
    $this->db=new Database($dbname);
    $this->dbname=$dbname;
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
<?php


class Model
{
private $db;
public function __construct($dbname){
    $this->db=new Database($dbname);
    $this->dbname=$dbname;
    echo"SHoutout";
    //$this->db
}

    /**
     * @return Database
     */
    public function getDb()
    {
        return $this->db;
    }

}
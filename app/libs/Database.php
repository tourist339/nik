<?php
class Database extends PDO {
    protected $host='localhost';
    protected $username='root';
    protected $password='';
    public function __construct($dbname)
    {
        parent::__construct("mysql:host='".$this->host.";dbname=".$dbname,$this->username,$this->password);
    }

}
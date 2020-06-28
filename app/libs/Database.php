<?php
class Database extends PDO {
    protected $host="localhost";
    protected $username="root";
    protected $password="root";
    public function __construct($dbname)
    {
        try {
            parent::__construct("mysql:dbname=" . $dbname . ";host=" . $this->host, $this->username, $this->password);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch (PDOException $e){
            echo "Error".$e;
        }
    }

}
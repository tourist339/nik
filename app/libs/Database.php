<?php
class Database extends PDO {
    private $host="localhost";
    private $username="root";
    private $password="root";
    public function __construct($dbname)
    {
        try {
            parent::__construct("mysql:dbname=" . $dbname . ";host=" . $this->host, $this->username, $this->password);
            $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch (PDOException $e){
            echo "Error".$e;
        }
    }

}
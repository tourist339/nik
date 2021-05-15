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

    protected $transactionCounter = 0;

    public function beginTransaction()
    {
        if (!$this->transactionCounter++) {
            return parent::beginTransaction();
        }
        $this->exec('SAVEPOINT trans'.$this->transactionCounter);
        return $this->transactionCounter >= 0;
    }

    public function commit()
    {
        if (!--$this->transactionCounter) {
            return parent::commit();
        }
        return $this->transactionCounter >= 0;
    }

    public function rollback()
    {
        if (--$this->transactionCounter) {
            $this->exec('ROLLBACK TO trans'. ($this->transactionCounter + 1));
            return true;
        }
        return parent::rollback();
    }

}
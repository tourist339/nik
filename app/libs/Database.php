<?php
class Database {
    const host="localhost";
    const username="root";
    const password="root";
    static $db;
    private $database;
    public function __construct($dbname)
    {
        try {
            $database=Database::getDatabase();


        }catch (PDOException $e){
            echo "Error".$e;
        }
    }

    public static function getDatabase(){
        if(self::$db==null) {
            self::$db = new PDO("mysql:dbname=" . DB_NAME . ";host=" . self::host, self::username, self::password);
//            ::__construct("mysql:dbname=" . $dbname . ";host=" . $this->host, $this->username, $this->password);
            self::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
            return self::$db;


    }


    protected $transactionCounter = 0;

    public function beginTransaction()
    {
        if (!$this->database->transactionCounter++) {
            return $this->beginTransaction();
        }
        $this->database->exec('SAVEPOINT trans'.$this->transactionCounter);
        return $this->database->transactionCounter >= 0;
    }

    public function commit()
    {
        if (!--$this->database->transactionCounter) {
            return $this->commit();
        }
        return $this->database->transactionCounter >= 0;
    }

    public function rollback()
    {
        if (--$this->database->transactionCounter) {
            $this->database->exec('ROLLBACK TO trans'. ($this->database->transactionCounter + 1));
            return true;
        }
        return $this->rollback();
    }

}
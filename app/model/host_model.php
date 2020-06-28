<?php


class host_model extends Model
{

    public function __construct()
    {
        parent::__construct("system_d");
    }

    public function create(){
        $db=$this->getDb();
        $query=$db->prepare("SELECT * FROM properties");
        $query->exec();
        var_dump($query->fetchAll(PDO::FETCH_ASSOC));
        //var_dump($db->);
    }
}
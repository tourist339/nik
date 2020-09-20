<?php


class city_model extends Model
{
    public function __construct($dbname = DB_NAME)
    {
        parent::__construct($dbname);
    }

    public function getMinMaxRent($city){
        $db=$this->getDb();
        try{
            $query=$db->prepare("SELECT min_rent,max_rent FROM cities WHERE city= :city");

            $query->execute(array(":city"=>$city));

            return $query->fetchAll(2)[0];


        }catch (PDOException $e){
            if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
            }
        }
    }
}
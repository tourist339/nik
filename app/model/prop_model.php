<?php


class prop_model extends Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function getAllProps($cols,$params){
        $db=$this->getDb();
        if(!empty($cols)){
           $columns=implode(",",$cols);
        }else{
            $columns="*";
        }
        $selectors="";
        if(isset($params["location"])) {
                $selectors .= "(city='" . $params["location"] . "' OR state='" . $params["location"]  . "')";

        }
        if(isset($params["search"])){
            $search=$params["search"];
            $selectors.=" AND title LIKE ?";
        }



       $q="SELECT ".$columns." FROM Properties WHERE ".$selectors;
        try {
            $stmt = $db->prepare($q);
            $stmt->execute(array("%$search%"));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            if(ERROR_DEBUG_MODE){
                print "ERROR  ".$e;
            }
            return null;
        }
    }
    public function getSingleProp($cols,$params){
        $db=$this->getDb();
        if(!empty($cols)){
            $columns=implode(",",$cols);
        }else{
            $columns="*";
        }
        $selectors="";
        foreach ($params as $key=>$value){
            $selectors.="(".$key."= '".$value. "') AND ";
        }
        $selectors=substr($selectors,0,-4);

        $q="SELECT ".$columns." FROM Properties WHERE ".$selectors;
        try {
            $stmt = $db->prepare($q);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            if(ERROR_DEBUG_MODE){
                print "ERROR  ".$e;
            }
            return null;
        }
    }

}
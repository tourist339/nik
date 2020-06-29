<?php


class prop_model extends Model
{
    public function __construct($dbname)
    {
        parent::__construct($dbname);

    }

    public function getData($cols,$loc,$prop=false,$name="",$id=""){
        $db=$this->getDb();
        if(!empty($cols)){
           $columns=implode(",",$cols);
        }else{
            $columns="*";
        }
        if($prop){
            $selectors="(id= '".$id. "' AND "."name= '".$name."') AND (city='".$loc."' OR state='".$loc."')";
        }else{
            $selectors="(city='".$loc."' OR state='".$loc."')";
        }

       $q="SELECT ".$columns." FROM Properties WHERE ".$selectors;
        try {
            $stmt = $db->prepare($q);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            if(DEBUG_MODE=="ON"){
                print "ERROR  ".$e;
            }
            return null;
        }
    }
}
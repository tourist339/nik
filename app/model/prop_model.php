<?php


class prop_model extends Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function getData($cols,$params){
        $db=$this->getDb();
        if(!empty($cols)){
           $columns=implode(",",$cols);
        }else{
            $columns="*";
        }
        $selectors="";
        foreach ($params as $key=>$value){
            if($key=="location"){
                $selectors.="(city='".$value."' OR state='".$value."')";
            }else{
                $selectors.="(".$key."= '".$value. "')";
            }
            $selectors.=" AND ";
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
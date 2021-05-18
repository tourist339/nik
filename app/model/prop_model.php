<?php


class prop_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllProps($cols,$params,$filters=[]){
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
        $search="";
        if(isset($params["search"]) and $params["search"]!=""){
            $search=$params["search"];
            $selectors.=" AND title LIKE :search";
        }
        if (!empty($filters)){
            foreach ($filters as $filter=>$val){
                switch ($filter){
                    case ":maxPrice":
                        $selectors.=" AND rent <= :maxPrice";
                        break;
                    case ":minPrice":
                        $selectors.=" AND rent >= :minPrice";
                        break;
                    case ":gender":
                        $selectors.=" AND gender = :gender";
                        break;
                    case ":pType":
                        $selectors.=" AND proptype = :pType";
                        break;
                    case ":bedrooms":
                        $selectors.=" AND bedrooms = :bedrooms";
                        break;
                    case ":bathrooms":
                        $selectors.=" AND bathrooms = :bathrooms";
                        break;
                    case ":kitchen":
                        $selectors.=" AND kitchen = :kitchen";
                        break;
                    case ":lyfly":
                        $selectors.=" AND lyfly = :lyfly";
                        break;
                    case ":amenities":
                        $selectors.=" AND amenities LIKE :amenities";
                        break;
                    case ":rules":
                        $selectors.=" AND houseRules LIKE :rules";
                        break;

                }
            }
        }



       $q="SELECT ".$columns." FROM Properties WHERE ".$selectors;
        try {
            $stmt = $db->prepare($q);
            if(!empty($filters)) {
                if(isset($params["search"]) and $search!="")
                    $filters[":search"] = "%$search%";
                $stmt->execute($filters);
            }else {
                if(isset($params["search"]) and $search!="")
                    $stmt->execute(array(":search"=>"%$search%"));
                else
                    $stmt->execute();

            }
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

    public function getPropertyById($propid,$params=[],$prop_type=Table::TEMP_PROPS){
        $db=$this->getDb();
        if(!empty($params)){
            $parameters=implode(",",$params);
        }else{
            $parameters="*";
        }
        if (!Table::isValidValue($prop_type)){
            $prop_type=Table::TEMP_PROPS;
        }
        $q="SELECT ".$parameters." FROM ".$prop_type." WHERE id=:id";


        try {
            $stmt = $db->prepare($q);
            $stmt->execute(array(":id"=>$propid));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            if(ERROR_DEBUG_MODE){
                print "ERROR  ".$e;
            }
            return null;
        }

    }

}
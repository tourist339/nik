<?php


class admin_model extends Model
{

    public function __construct($dbname = DB_NAME)
    {
        parent::__construct($dbname);
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function checkCredentials($username,$password){
        try {
            $db = $this->getDb();
            $q = "SELECT password FROM admins WHERE username=:user";
            $stmt = $db->prepare($q);
            $stmt->execute(array(":user" => $username));
            $hashed_password= $stmt->fetchColumn();
            if(password_verify($password,$hashed_password)){
                return true;
            }else{
                return false;
            }
        }
        catch (Exception $e){
            echo $e;
        }
    }

    /**
     * @param $userid
     */
    public function getUser($userid){
        try {
            $db = $this->getDb();
            $q = "SELECT * FROM users WHERE id=:id";
            $stmt = $db->prepare($q);
            $stmt->execute(array(":id" => $userid));
            var_dump($stmt->fetchAll());
        }
        catch (Exception $e){
            echo $e;
        }
    }

    /**
     * @param $data
     * @return string
     */
    public function createPropRow($data){
        $db=$this->getDb();
        var_dump(count($data));
        if(isset($db) && isset($data)){
            try{
                //rearramge
                $query=$db->prepare("INSERT INTO temp_properties( ownerid,title, description, city, state,aptno,
                                                                    proptype, sharingtype, guests, bedrooms, bathrooms,
                                                                    kitchen, address, rent, amenities,images,gender,houseRules,lyfly,agreementType)
                                                                     VALUES
                                                                (:ownerid,:title,:description,:city,:state,:aptno,:proptype,:sharingtype,:guests,:bedrooms,
                                                                :bathrooms,:kitchen,:address,
                                                                :rent,:amenities,:images,:gender,:houseRules,:lyfly,:agreementType)"
                );
                $query->execute($data);
                return $db->lastInsertId();


            }catch (PDOException $e){
                if(ERROR_DEBUG_MODE){
                    echo "Error".$e; // For debugging
                }
            }
        }
    }

    /**
     * @param $cityname
     * @param array $params
     * @return array|null
     */
    public function getUnapproveProps($cityname,$params=[]){
        $db=$this->getDb();
        if(!empty($params)){
            $parameters=implode(",",$params);
        }else{
            $parameters="*";
        }
        $whereclause="";
       if(!empty($cityname)){
           $whereclause="WHERE city = :cityname OR state= :statename";
       }

        $q="SELECT ".$parameters." FROM temp_properties ".$whereclause;
        try {
            $stmt = $db->prepare($q);
            if(!empty($cityname))
                $stmt->execute(array(":cityname"=>$cityname,":statename"=>$cityname));
            else
                $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            if(ERROR_DEBUG_MODE){
                print "ERROR  ".$e;
            }
            return null;
        }
    }

    /**
     * @param $propid
     * @param array $params
     * @return array|null
     */

    public function getPropertyById($propid,$params=[]){
        $db=$this->getDb();
        if(!empty($params)){
            $parameters=implode(",",$params);
        }else{
            $parameters="*";
        }
        $q="SELECT ".$parameters." FROM temp_properties WHERE id=:id";

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

    public function addPropToMainTable($data){
        $db=$this->getDb();
        if(isset($db) && isset($data)) {
            try {
                $query = $db->prepare("INSERT INTO Properties( ownerid,title, description, city, state,aptno,
                                                                    proptype, sharingtype, guests, bedrooms, bathrooms,
                                                                    kitchen, address, rent, amenities,utilities,agreementType,dateAdded,gender,houseRules,images,admin,lyfly)
                                                                     VALUES
                                                                (:ownerid,:title, :description, :city, :state,:aptno,
                                                                    :proptype, :sharingtype, :guests, :bedrooms, :bathrooms,
                                                                    :kitchen, :address, :rent, :amenities,:utilities,:agreementType,:dateAdded,:gender,:houseRules,:images, :admin,:lyfly)"
                );
                $query->execute($data);
                $lastid = $db->lastInsertId();
                $this->checkCityExists($data[":city"],$data[":rent"]);
                return $lastid;
            } catch (PDOException $e) {
                if (ERROR_DEBUG_MODE) {
                    echo "Error" . $e; // For debugging
                }
                return DB_ERROR_CODE;

            }
        }
    }

    private function checkCityExists($city,$rent){
        try{
            $db=$this->getDb();
            $query=$db->prepare("SELECT min_rent,max_rent FROM cities WHERE city=:city");
            $query->execute(array(":city"=>$city));

            if($query->rowCount()>0){ //i.e. city already exists in the cities table then update min and max rent
                $rents=$query->fetchAll(PDO::FETCH_ASSOC)[0];
                $min_rent=$rents["min_rent"];
                $max_rent=$rents["max_rent"];
                if ($rent<$min_rent){
                    $this->updateCityRent("min_rent",$rent,$city);
                }elseif($rent>$max_rent){
                    $this->updateCityRent("max_rent",$rent,$city);
                }

            }else{
                $this->insertNewCity($city,$rent);
            }
        }catch (PDOException $e) {
            if (ERROR_DEBUG_MODE) {
                echo "Error" . $e; // For debugging
            }
            return DB_ERROR_CODE;

        }
    }

    private function updateCityRent($min_or_max,$rent,$city){
        try{
            $db=$this->getDb();
            $query=$db->prepare("UPDATE cities SET ".$min_or_max." = :rent
                                                        WHERE city = :city");

            $query->execute(array(":city"=>$city,":rent"=>$rent));


        }catch (PDOException $e) {
            if (ERROR_DEBUG_MODE) {
                echo "Error" . $e; // For debugging
            }
            return DB_ERROR_CODE;

        }
    }


    private  function insertNewCity($city,$rent){
        try{
            $db=$this->getDb();
            $query=$db->prepare("INSERT INTO cities(city,min_rent,max_rent)
                                                        VALUES(:city,:minrent,:maxrent) ");

            $query->execute(array(":city"=>$city,":minrent"=>$rent,":maxrent"=>$rent));


        }catch (PDOException $e) {
            if (ERROR_DEBUG_MODE) {
                echo "Error" . $e; // For debugging
            }
            return DB_ERROR_CODE;

        }

    }

    public function deleteTempRow($propid){
        $db=$this->getDb();

        try{
            $query=$db->prepare("DELETE FROM temp_properties WHERE id=:id");
            $query->execute(array(":id"=>$propid));

        }catch (PDOException $e){
            if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
            }
            return DB_ERROR_CODE;

        }
    }

}
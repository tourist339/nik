<?php


class admin_model extends Model
{

    public function __construct($dbname = DB_NAME)
    {
        parent::__construct($dbname);
    }

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

    public function createPropRow($data){
        $db=$this->getDb();
        if(isset($db) && isset($data)){
            try{
                $query=$db->prepare("INSERT INTO temp_properties( ownerid,title, description, city, state,aptno,
                                                                    proptype, sharingtype, guests, bedrooms, bathrooms,
                                                                    kitchen, address, rent, amenities,images,gender,houseRules,lyfly,agreementType)
                                                                     VALUES
                                                                (:ownerid,:pTitle,:pDesc,:pCity,:pState,:pApt,:pType,:pSharingType,:pNoGuests,:pNoBeds,
                                                                :pNoBathrooms,:pKitchenAvailable,:pAddress,
                                                                :pRent,:amenities,:images,:pGender,:hRules,:pLyfly,:pAgreement)"
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
//            $l=$data[":lyfly"];
            var_dump($data);
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

                return $lastid;
            } catch (PDOException $e) {
                if (ERROR_DEBUG_MODE) {
                    echo "Error" . $e; // For debugging
                }
                return DB_ERROR_CODE;

            }
        }
    }

    private function checkCityExists(){

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
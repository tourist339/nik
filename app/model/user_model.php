<?php


class user_model extends Model
{
    private $userid;
    public function __construct($userid)
    {
        $this->userid=$userid;
        parent::__construct();
    }

    public function createUser($data,$login_type){
        try {
            echo $login_type;
            var_dump($data);
            $db = $this->getDb();

            if ($login_type == "google") {
                $pswd = password_hash($data["sub"], PASSWORD_BCRYPT);
            }
            $q = "INSERT INTO users(first_name,last_name,login_type,email,pic,password,phone_num,address,city,state)
            VALUES(:first_name,:last_name,:login_type,:email,:pic,:password,:phone_num,:address,:city,:state)";
            $stmt = $db->prepare($q);
            $stmt->execute(array($data["given_name"], $data["family_name"], $login_type, $data["email"], $data["picture"], $pswd,
                $data["pNum"], $data["address"], $data["city"], $data["state"]));
        }catch (PDOException $e){
            if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
            }
            return DB_ERROR_CODE;

        }

    }

    public function userExists($email,$login_type){
        try {
            $stmt = $this->getDb()->prepare("SELECT count(*) FROM users WHERE email=? AND login_type=?");
            $stmt->bindParam(1, $email, PDO::PARAM_STR);
            $stmt->bindParam(2, $login_type, PDO::PARAM_STR);

            $stmt->execute();
            $record = $stmt->fetchColumn();
            return $record > 0;
        }catch (PDOException $e){
            if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
            }
            return DB_ERROR_CODE;

        }

    }

    public function getUserId($email,$login_type){
        try {
            $q = "SELECT id FROM users WHERE email= :email AND login_type= :login_type";
            $stmt = $this->getDb()->prepare($q);
            $stmt->execute(array(":email" => $email, ":login_type" => $login_type));
            return $stmt->fetchColumn();
        }catch (PDOException $e){
            if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
            }
            return DB_ERROR_CODE;

        }

    }
    public function getUserDataByEmail($params,$email,$login_type){
        try {
            $params = implode(",", $params);
            $q = "SELECT " . $params . " FROM users WHERE email= :email AND login_type= :login_type";
            $stmt = $this->getDb()->prepare($q);
            $stmt->execute(array(":email" => $email, ":login_type" => $login_type));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
            }
            return DB_ERROR_CODE;

        }

    }
    public function getUserDataByID($params,$id){
        try {
            $params = implode(",", $params);
            $q = "SELECT " . $params . " FROM users WHERE id = :id";
            $stmt = $this->getDb()->prepare($q);
            $stmt->execute(array(":id" => $id));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
            }
            return DB_ERROR_CODE;

        }

    }
    private function updateUser($param,$paramvalue,$id){

        try {

            $q="UPDATE users SET ".$param."=:".$param." WHERE id = :id";
            $stmt = $this->getDb()->prepare($q);
            $stmt->execute(array(":" . $param => $paramvalue, ":id" => $id));
        }catch (PDOException $e){
            if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
            }
            return DB_ERROR_CODE;

        }
    }


    public function updateUnApprovedProperties($propid){
        $this->updateWishOrProp("unapproved_properties",$propid,$this->userid);
    }
    public function updateApprovedProperties($propid){
        $this->updateWishOrProp("approved_properties",$propid,$this->userid);
    }
    public function updateUnfinishedProperties($propi){
        return $this->updateWishOrProp("unfinished_properties",$propid,$this->userid);


    }

    public function updateWishlist($propid){
        $this->updateWishOrProp("wishlist",$propid,$this->userid);
    }
    private function updateWishOrProp($column,$propid){
        try {
            $lists=$this->getWishlistOrProp($column,$this->userid);
            $current_list=$lists[0];
            $current_list_array=$lists[1];

            if (!empty(trim($current_list))) {
                if(!in_array($propid,$current_list_array)) {
                    $current_list .= ("," . $propid); //add a comma seperated prop id to list of properties
                }else{

                    echo "Prop already in the ".$column;
                }
            }
            else
                $current_list=$propid;
            $this->updateUser($column,$current_list,$this->userid);
            var_dump($current_list_array);
            $c=count($current_list_array);
            if ($c==1){
                if ($current_list_array[0]=="")
                    $c=0;
            }
            return $c+1; //return number of items in new list

        }catch (PDOException $e){
            if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
            }
            return DB_ERROR_CODE;
        }
    }

    private function getWishlistOrProp($toget)
    {
        $q = "SELECT ".$toget." FROM users WHERE id= :id ";
        $stmt = $this->getDb()->prepare($q);
        $stmt->execute(array(":id" => $this->userid));

        $current_list = $stmt->fetchColumn();
        $current_list_array = explode(",", $current_list);
        if (count($current_list_array)==1 && $current_list_array[0]=="")
            $current_list_array=array();

        return array($current_list,$current_list_array);
    }

    public function getIndexesUnfinishedProperty(){
        $all_unfinished_props = $this->getWishlistOrProp("unfinished_properties", $this->userid)[1];

        $indexes=array();
        $i=0;
        foreach ($all_unfinished_props as $up){
            $indexes[$up]=$i;
            $i++;
        }
        return $indexes;

    }

    public function getUnfinishedPropertyUsingIndex($index){
        if($index>=0) {
            $all_unfinished_props = $this->getWishlistOrProp("unfinished_properties", $this->userid)[1];

            if ($index < count($all_unfinished_props) &&!(count($all_unfinished_props)==1 && $all_unfinished_props[0]=="")) {
                return $all_unfinished_props[$index];
            }
        }else if ($index==-1){
            $all_unfinished_props = $this->getWishlistOrProp("unfinished_properties", $this->userid)[1];
            if (count($all_unfinished_props)!=0)
                return end($all_unfinished_props);

        }
        return -1;
    }


    public function checkPropInWishlist($propid){
        return $this->checkPropInColumn("wishlist",$propid,$this->userid);
    }



    public function checkPropInUnfinishedProperties($propid){
        return $this->checkPropInColumn("unfinished_properties",$propid,$this->userid);
    }

    private function checkPropInColumn($column,$propid){
        try {
            $wishlist_data=$this->getWishlistOrProp($column,$this->userid);
            $current_list=$wishlist_data[0];
            $current_list_array=$wishlist_data[1];
            if (!empty(trim($current_list))) {
                if (!in_array($propid, $current_list_array)) {
                    return false;
                } else {
                    return true;
                }
            }
        }
        catch (PDOException $e){
            if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
            }
            return DB_ERROR_CODE;

        }
    }

    private function removeSingleValueFromColumn($column,$value_to_remove){
        try {
            $wishlist_data=$this->getWishlistOrProp($column,$this->userid);
            $current_list_array=$wishlist_data[1];
            $updated_list=Helper::removeValueFromArray($current_list_array,$value_to_remove);
            $this->updateUser($column,$updated_list,$this->userid);
            return $value_to_remove;

        }
        catch (PDOException $e){
            if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
            }
            return DB_ERROR_CODE;

        }
    }


    public function removePropFromWishlist($propid){
        return $this->removeSingleValueFromColumn("wishlist",$propid,$this->userid);

    }



    public function removeUnapprovedProp($propid){

        return $this->removeSingleValueFromColumn("unapproved_properties",$propid,$this->userid);
    }

    public function removeUnfinishedProperty($index){

        $propid=$this->getUnfinishedPropertyUsingIndex($index,$this->userid);

        if($propid!=-1){
            return $this->removeSingleValueFromColumn("unfinished_properties",$propid,$this->userid);
        }else{
            return DB_ERROR_CODE;
        }
    }


    public function hasUnfinishedProperty(){
        return !empty(trim($this->getWishlistOrProp("unfinished_properties",$this->userid)[0]));
    }

    public function getUnfinishedProperties()
    {
        try{
            $query="SELECT id,title,dateAdded,dateUpdated FROM ".TABLE_UNFINISHED_PROPS." WHERE ownerid=:id ORDER BY dateUpdated";
            $stmt=$this->getDb()->prepare($query);
            $stmt->execute(array(":id"=>$this->userid));
            return $stmt->fetchAll();
        } catch (PDOException $e){
            if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
            }
            return DB_ERROR_CODE;

        }

    }
}
<?php


class user_model extends Model
{
    public function __construct()
    {
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
            echo "here we go";
            var_dump($param);
            var_dump($paramvalue);
            echo "here we end";
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


    public function updateUnApprovedProperties($propid,$userid){
        $this->updateWishOrProp("unapproved_properties",$propid,$userid);
    }
    public function updateApprovedProperties($propid,$userid){
        $this->updateWishOrProp("approved_properties",$propid,$userid);
    }
    public function updateUnfinishedProperties($propid,$userid){
        return array($this->updateWishOrProp("unfinished_properties",$propid,$userid),
            count($this->getWishlistOrProp("unfinished_properties",$userid)[1])-1);

    }

    public function updateWishlist($propid,$userid){
        $this->updateWishOrProp("wishlist",$propid,$userid);
    }
    private function updateWishOrProp($column,$propid,$userid){
        try {
            $lists=$this->getWishlistOrProp($column,$userid);
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

            $this->updateUser($column,$current_list,$userid);
        }catch (PDOException $e){
            if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
            }
            return DB_ERROR_CODE;
        }
    }

    private function getWishlistOrProp($toget,$userid)
    {
        $q = "SELECT ".$toget." FROM users WHERE id= :id ";
        $stmt = $this->getDb()->prepare($q);
        $stmt->execute(array(":id" => $userid));

        $current_list = $stmt->fetchColumn();
        $current_list_array = explode(",", $current_list);

        return array($current_list,$current_list_array);
    }

    public function getIndexesUnfinishedUsingProperty($userid){
        $all_unfinished_props = $this->getWishlistOrProp("unfinished_properties", $userid)[1];

        $indexes=array();
        $i=0;
        foreach ($all_unfinished_props as $up){
            $indexes[$up]=$i;
        }
        return $indexes;

    }

    public function getUnfinishedPropertyUsingIndex($index,$userid){
        if($index>=0) {
            $all_unfinished_props = $this->getWishlistOrProp("unfinished_properties", $userid)[1];

            if ($index < count($all_unfinished_props)) {
                return $all_unfinished_props[$index];
            }
        }
    }


    public function checkPropInWishlist($propid,$userid){
        return $this->checkPropInColumn("wishlist",$propid,$userid);
    }



    public function checkPropInUnfinishedProperties($propid,$userid){
        return $this->checkPropInColumn("unfinished_properties",$propid,$userid);
    }

    private function checkPropInColumn($column,$propid,$userid){
        try {
            $wishlist_data=$this->getWishlistOrProp($column,$userid);
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

    private function removeSingleValueFromColumn($column,$value_to_remove,$userid){
        try {
            $wishlist_data=$this->getWishlistOrProp($column,$userid);
            $current_list_array=$wishlist_data[1];
            $updated_list=Helper::removeValueFromArray($current_list_array,$value_to_remove);
            $this->updateUser("unapproved_properties",$updated_list,$userid);

        }
        catch (PDOException $e){
            if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
            }
            return DB_ERROR_CODE;

        }
    }


    public function removePropFromWishlist($propid,$userid){
        return $this->removeSingleValueFromColumn("wishlist",$propid,$userid);

    }



    public function removeUnapprovedProp($propid,$userid){

        return $this->removeSingleValueFromColumn("unapproved_properties",$propid,$userid);
    }


    public function hasUnfinishedProperty($userid){
        return !empty(trim($this->getWishlistOrProp("unfinished_properties",$userid)[0]));
    }

    public function getUnfinishedProperties($userid)
    {
        try{
            $query="SELECT id,dateAdded,dateUpdated FROM ".TABLE_UNFINISHED_PROPS." WHERE ownerid=:id ORDER BY dateUpdated";
            $stmt=$this->getDb()->prepare($query);
            $stmt->execute(array(":id"=>$userid));
            return $stmt->fetchAll();
        } catch (PDOException $e){
            if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
            }
            return DB_ERROR_CODE;

        }

    }
}
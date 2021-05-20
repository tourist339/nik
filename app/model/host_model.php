<?php


class host_model extends Model
{

    const NEW_PROP="new";
    const UPDATE_PROP="update";
    private $prop_columns=array("ownerid","title","description","city","state","aptno"
    ,"proptype","sharingtype","guests","bedrooms","bathrooms","kitchen","address","rent","amenities",
    "agreementType","gender","houseRules","lyfly");
    private $prop_update_string;
    public function __construct()
    {
        parent::__construct();

    }

    public function createUnfinishedPropRow($data){
        return $this->createPropRowHelper($data,TABLE_UNFINISHED_PROPS,self::NEW_PROP);

    }

    public function updateUnfinishedPropRow($prop_id,$data){
        return $this->updatePropRowHelper($data,TABLE_UNFINISHED_PROPS,$prop_id);

    }
    public function createPropRow($data){
       $this->createPropRowHelper($data,TABLE_MAIN_PROPS,self::UPDATE_PROP);
    }

    private function updatePropRowHelper($data,$table_name,$prop_id){
        try {
            $update_string="";
            foreach ($this->prop_columns as $col){
                $update_string.=$col;
                $update_string.="=:";
                $update_string.=$col;
                $update_string.=",";
            }
            $update_string.="dateUpdated=DATE(UTC_TIMESTAMP())";

            $update_query = "UPDATE $table_name SET ".$update_string." WHERE id=:id";


//            (:ownerid,:pTitle,:pDesc,:pCity,:pState,:pApt,:pType,:pSharingType,:pNoGuests,:pNoBeds,
//                                                                :pNoBathrooms,:pKitchenAvailable,:pAddress,
//                                                                :pRent,:amenities,:pGender,:hRules,:pLyfly,:pAgreement)
            $query = $this->getDb()->prepare($update_query);
            $data[":id"] = $prop_id;
            $query->execute($data);
        }catch (PDOException $e){
            if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
            }
            return DB_ERROR_CODE;

        }

    }

    private function createPropRowHelper($data,$table_name){
        $db=$this->getDb();



        if(isset($db) && isset($data)){
            //left utilities to insert, will do that later

            try{
                $query=$db->prepare("INSERT INTO `$table_name`( ownerid,title, description, city, state,aptno,
                                                                    proptype, sharingtype, guests, bedrooms, bathrooms,
                                                                    kitchen, address, rent, amenities,agreementType,gender,dateAdded,dateUpdated,houseRules,lyfly)
                                                                     VALUES
                                                                   (:ownerid,:title,:sharingtype,:description,:city,:state,:aptno,:proptype,:guests,:bedrooms,
                                                                :bathrooms,:kitchen,:address,
                                                                :rent,:amenities,:gender,:houseRules,DATE(NOW()),DATE(NOW()),:lyfly,:agreementType)"
                );

                $query->execute($data);
                return $db->lastInsertId();


            }catch (PDOException $e){
                if(ERROR_DEBUG_MODE){
                    echo "Error".$e; // For debugging
                }
                    return DB_ERROR_CODE;

            }
        }
    }
}
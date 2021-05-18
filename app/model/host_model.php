<?php


class host_model extends Model
{

    const NEW_PROP="new";
    const UPDATE_PROP="update";
    private $prop_columns=array("ownerid","title","description","city","state","aptno"
    ,"proptype","sharingtype","guests","bedrooms","bathrooms","kitchen","address","rent","amenities",
    "agreementType","dateAdded","gender","houseRules","lyfly");
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

            $update_string = "UPDATE $table_name SET ownerid=:ownerid , title=:pTitle,description=:pDesc,
        city=:pCity,state=:pState,aptno=:pApt,proptype=:pType,sharingtype=:pSharingType,guests=:pNoGuests,bedrooms=:pNoBeds,bathrooms=:pNoBathrooms,
        kitchen=:pKitchenAvailable,address=:pAddress,rent=:pRent,amenities=:amenities,agreementType=:pAgreement,gender=:pGender,dateUpdated=DATE(UTC_TIMESTAMP())
                 ,houseRules=:hRules,lyfly=:pLyfly WHERE id=:id
                ";

//            (:ownerid,:pTitle,:pDesc,:pCity,:pState,:pApt,:pType,:pSharingType,:pNoGuests,:pNoBeds,
//                                                                :pNoBathrooms,:pKitchenAvailable,:pAddress,
//                                                                :pRent,:amenities,:pGender,:hRules,:pLyfly,:pAgreement)
            $query = $this->getDb()->prepare($update_string);
            $data[":id"] = $prop_id;
            $query->execute($data);
        }catch (PDOException $e){
            if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
            }
            return DB_ERROR_CODE;

        }

    }

    private function createPropRowHelper($data,$table_name,$mode){
        $db=$this->getDb();



        if(isset($db) && isset($data)){
            //left utilities to insert, will do that later

            try{
                $query=$db->prepare("INSERT INTO `$table_name`( ownerid,title, description, city, state,aptno,
                                                                    proptype, sharingtype, guests, bedrooms, bathrooms,
                                                                    kitchen, address, rent, amenities,agreementType,gender,dateAdded,dateUpdated,houseRules,lyfly)
                                                                     VALUES
                                                                   (:ownerid,:pTitle,:pDesc,:pCity,:pState,:pApt,:pType,:pSharingType,:pNoGuests,:pNoBeds,
                                                                :pNoBathrooms,:pKitchenAvailable,:pAddress,
                                                                :pRent,:amenities,:pGender,:hRules,DATE(NOW()),DATE(NOW()),:pLyfly,:pAgreement)"
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
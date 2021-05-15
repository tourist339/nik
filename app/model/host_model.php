<?php


class host_model extends Model
{

    const NEW_PROP="new";
    const UPDATE_PROP="update";
    public function __construct()
    {
        parent::__construct();
    }

    public function createUnfinishedPropRow($data){
        return $this->createPropRowHelper($data,TABLE_UNFINISHED_PROPS,self::NEW_PROP);

    }

    public function updateUnfinishedPropRow($prop_id,$data){

    }
    public function createPropRow($data){
       $this->createPropRowHelper($data,TABLE_MAIN_PROPS,self::UPDATE_PROP);
    }

    private function createPropRowHelper($data,$table_name,$mode){
        $db=$this->getDb();

        var_dump($data);
        $column_string="( ownerid,title, description, city, state,aptno,
                                                                    proptype, sharingtype, guests, bedrooms, bathrooms,
                                                                    kitchen, address, rent, amenities,utilities,agreementType,dateAdded,gender,houseRules,images,lyfly)";
        if(isset($db) && isset($data)){
            //left utilities to insert, will do that later

            var_dump($table_name);
            try{
                $query=$db->prepare("INSERT INTO $table_name( ownerid,title, description, city, state,aptno,
                                                                    proptype, sharingtype, guests, bedrooms, bathrooms,
                                                                    kitchen, address, rent, amenities,agreementType,gender,houseRules,lyfly)
                                                                     VALUES
                                                                   (:ownerid,:pTitle,:pDesc,:pCity,:pState,:pApt,:pType,:pSharingType,:pNoGuests,:pNoBeds,
                                                                :pNoBathrooms,:pKitchenAvailable,:pAddress,
                                                                :pRent,:amenities,:pGender,:hRules,:pLyfly,:pAgreement)"
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
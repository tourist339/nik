<?php


class host_model extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function createPropRow($data){
        $db=$this->getDb();
        if(isset($db) && isset($data)){
            //left ownerid and utilities to insert, will do that later

            try{
                $query=$db->prepare("INSERT INTO Properties( ownerid,title, description, city, state,aptno,
                                                                    proptype, sharingtype, guests, bedrooms, bathrooms,
                                                                    kitchen, bathroomshared, address, rent, amenities,images)
                                                                     VALUES
                                                                (:ownerid,:pTitle,:pDesc,:pCity,:pState,:pApt,:pType,:pSharingType,:pNoGuests,:pNoBeds,
                                                                :pNoBathrooms,:pKitchenAvailable,:pBathroomShared,:pAddress,
                                                                :pRent,:amenities,:images)"
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
}
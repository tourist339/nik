<?php


class host_model extends Model
{

    public function __construct()
    {
        parent::__construct("system_d");
    }

    public function createPropRow($data){
        $db=$this->getDb();
        if(isset($db) && isset($data)){
            //left ownerid and utilities to insert, will do that later

            try{
                echo "hey".$data[":pNoGuests"]."there";
                $query=$db->prepare("INSERT INTO Properties( title, description, city, state,aptno,
                                                                    proptype, sharingtype, guests, bedrooms, bathrooms,
                                                                    kitchen, bathroomshared, address, rent, amenities) VALUES
                                                                (:pTitle,:pDesc,:pCity,:pState,:pApt,:pType,:pSharingType,:pNoGuests,:pNoBeds,
                                                                :pNoBathrooms,:pKitchenAvailable,:pBathroomShared,:pAddress,
                                                                :pRent,:amenities)"
                                                                                        );
                $query->execute($data);

            }catch (PDOException $e){
                echo "Error".$e; // For debugging
            }
        }
    }
}
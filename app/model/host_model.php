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
                                                                    kitchen, bathroomshared, address, rent, amenities,utilities,images,admin)
                                                                     VALUES
                                                                (:ownerid,:title, :description, :city, :state,:aptno,
                                                                    :proptype, :sharingtype, :guests, :bedrooms, :bathrooms,
                                                                   :kitchen, :bathroomshared, :address, :rent, :amenities,:utilities,:images, :admin)"
                                                                                        );
                $query->execute($data);
                var_dump($db->lastInsertId());


            }catch (PDOException $e){
                if(ERROR_DEBUG_MODE){
                echo "Error".$e; // For debugging
                    }
            }
        }
    }
}
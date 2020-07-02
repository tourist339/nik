<?php
$q="CREATE TABLE `Properties` (
 `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
 `ownerid` int(9) unsigned DEFAULT NULL,
 `title` varchar(30) DEFAULT NULL,
 `description` varchar(500) DEFAULT NULL,
 `city` varchar(40) DEFAULT NULL,
 `state` varchar(40) DEFAULT NULL,
 `aptno` varchar(255) DEFAULT NULL,
 `proptype` varchar(40) DEFAULT NULL,
 `sharingtype` varchar(40) DEFAULT NULL,
 `guests` int(2) unsigned DEFAULT NULL,
 `bedrooms` int(2) unsigned DEFAULT NULL,
 `bathrooms` int(2) unsigned DEFAULT NULL,
 `kitchen` int(1) unsigned DEFAULT NULL,
 `bathroomshared` int(1) unsigned DEFAULT NULL,
 `address` varchar(200) DEFAULT NULL,
 `rent` int(8) unsigned DEFAULT NULL,
 `amenities` varchar(200) DEFAULT NULL,
 `utilities` varchar(60) DEFAULT NULL,
 PRIMARY KEY (`id`)
)";
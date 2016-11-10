<?php

#use order_id and customer_id as foreign keys

$installer = $this;
$installer->startSetup();
/*
$installer->run("
create TABLE `{$installer->getTable('ami/subscriptioninfo')}` 
  `item_id` int UNSIGNED NOT NULL auto_increment COMMENT 'Item id' ,
 `subscriber_firstname` VARCHAR( 255 ) NOT NULL,
 `subscriber_lastname` VARCHAR( 255 ) NOT NULL;
  ");
*/
  

  $installer->run("
create TABLE `subscriptioninfo_quotes` (
  `item_id` int UNSIGNED NOT NULL auto_increment COMMENT 'Item id' ,
    `quote_item_id` int,
 `subscriber_firstname` VARCHAR( 50 ) NOT NULL,
 `subscriber_lastname` VARCHAR( 50 ) NOT NULL,
 `subscriber_address` VARCHAR( 100 ) NOT NULL,
 `subscriber_address2` VARCHAR( 100 ) NOT NULL,
 `subscriber_city` VARCHAR( 50 ) NOT NULL,
 `subscriber_state` VARCHAR( 50 ) NOT NULL,
 `subscriber_zip` VARCHAR( 12 ) NOT NULL,
 `subscriber_email` VARCHAR( 100 ) NOT NULL,
 `subscriber_phone` VARCHAR( 20 ) NOT NULL,
     `whenentered` date not null,
     PRIMARY KEY (item_id));  
     
     create TABLE `subscriptioninfo_orders` (
  `item_id` int UNSIGNED NOT NULL auto_increment COMMENT 'Item id' ,
    `order_item_id` int NOT NULL,
    `order_id` int NOT NULL,
    `order_increment_id` int NOT NULL,
    `customer_id` int NOT NULL,
 `subscriber_firstname` VARCHAR( 50 ) NOT NULL,
 `subscriber_lastname` VARCHAR( 50 ) NOT NULL,
 `subscriber_address` VARCHAR( 100 ) NOT NULL,
 `subscriber_address2` VARCHAR( 100 ) NOT NULL,
 `subscriber_city` VARCHAR( 50 ) NOT NULL,
 `subscriber_state` VARCHAR( 50 ) NOT NULL,
 `subscriber_zip` VARCHAR( 12 ) NOT NULL,
 `subscriber_email` VARCHAR( 100 ) NOT NULL,
 `subscriber_phone` VARCHAR( 20 ) NOT NULL,
     `whenentered` date not null,
     PRIMARY KEY (item_id));     
  ");

        
        
  


/*
$installer->run("
create TABLE `subscriptioninfo_orders` (
  `item_id` int UNSIGNED NOT NULL auto_increment COMMENT 'Item id' ,
    `quote_item_id` int,
    `order_item_id` int,
    `order_id` int,
    `customer_id` int,
 `subscriber_firstname` VARCHAR( 50 ) NOT NULL,
 `subscriber_lastname` VARCHAR( 50 ) NOT NULL,
 `subscriber_address` VARCHAR( 100 ) NOT NULL,
 `subscriber_address2` VARCHAR( 100 ) NOT NULL,
 `subscriber_city` VARCHAR( 50 ) NOT NULL,
 `subscriber_state` VARCHAR( 50 ) NOT NULL,
 `subscriber_zip` VARCHAR( 12 ) NOT NULL,
 `subscriber_email` VARCHAR( 100 ) NOT NULL,
 `subscriber_phone` VARCHAR( 20 ) NOT NULL,
     `whenentered` date not null,
     PRIMARY KEY (item_id));     
  ");
*/




$installer->endSetup();


?>
<?php
$installer = $this;

$installer->startSetup();

$installer->run("


CREATE TABLE {$this->getTable('fileupload')} (
  `fileupload_id` int(11) unsigned NOT NULL auto_increment,  
  `title` varchar(255) NOT NULL default '',                          
  `filename` varchar(255) NOT NULL default '',                       
  `file_icon` text,                                                  
  `file_type` varchar(255) default '',                               
  `file_size` varchar(255) default '',                               
  `download_link` text,                                              
  `downloads` int(11) default '0',                                   
  `content` text NOT NULL,                                           
  `status` smallint(6) NOT NULL default '0',                         
  `cmspage_id` text,                                                 
  `created_time` datetime default NULL,                              
  `update_time` datetime default NULL,
  `customer_group_id` tinyint(4) default '0',
  `limit_downloads` int(11) default NULL,
  `cat_id` int(11) default NULL,
  PRIMARY KEY  (`fileupload_id`)                             
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


CREATE TABLE {$this->getTable('fileupload_store')} (
`fileupload_id` int(11) unsigned NOT NULL,                       
`store_id` smallint(5) unsigned NOT NULL,                                
PRIMARY KEY  (`fileupload_id`,`store_id`),                       
KEY `FK_PRODUCTATTACHMENTS_STORE_STORE` (`store_id`)                     
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='File Uploads Stores';


CREATE TABLE {$this->getTable('fileupload_products')} (
  `product_related_id` int(11) NOT NULL auto_increment,   
   `fileupload_id` int(11) default NULL,           
   `product_id` int(11) default NULL,                      
   UNIQUE KEY `product_related_id` (`product_related_id`)  
 ) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;


CREATE TABLE {$this->getTable('fileupload_category_link')} (
  `fileupload_category_link_id` int(11) NOT NULL auto_increment,
  `category_id` int(11) default NULL,
  `fileupload_id` int(11) default NULL,
  UNIQUE KEY `fileupload_category_link_id` (`fileupload_category_link_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE {$this->getTable('fileupload_cats')} (                               
   `category_id` int(11) unsigned NOT NULL auto_increment,               
   `category_name` varchar(254) NOT NULL default '',                     
   `category_status` tinyint(1) NOT NULL default '1',                    
   `category_url_key` varchar(254) default NULL,                         
   `category_order` int(10) NOT NULL default '1',                        
   `meta_keywords` text,                                                 
   `meta_description` text,                                              
   `left_node` int(11) default NULL,                                     
   `right_node` int(11) default NULL,                                    
   PRIMARY KEY  (`category_id`),                                         
   KEY `fileupload_category_index_name` (`category_name`),       
   KEY `fileupload_category_index_url_key` (`category_url_key`)  
 ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
 
CREATE TABLE {$this->getTable('fileupload_category_store')} (                      
 `category_id` int(10) unsigned NOT NULL,                             
 `store_id` smallint(5) unsigned NOT NULL,                            
 PRIMARY KEY  (`category_id`,`store_id`),                             
 KEY `fileupload_category_store_index_store_id` (`store_id`)  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
						 
");

$installer->setConfigData('fileupload/general/show_counter','1');
$installer->setConfigData('fileupload/general/login_before_download','1');

$installer->setConfigData('fileupload/fileupload/enabled','1');
$installer->setConfigData('fileupload/fileupload/product_attachment_heading','Downloads');
$installer->setConfigData('fileupload/fileupload/showcontent','0');

$installer->setConfigData('fileupload/cmspagesattachments/enabled','1');
$installer->setConfigData('fileupload/cmspagesattachments/cms_page_attachment_heading','Downloads');
$installer->setConfigData('fileupload/cmspagesattachments/showcontent','0');


$installer->endSetup(); 
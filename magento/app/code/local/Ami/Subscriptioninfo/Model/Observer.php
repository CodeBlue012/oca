<?php
	
	
class Ami_Subscriptioninfo_Model_Observer //extends Mage_Core_Model_Abstract
{
    public function logCartAdd(Varien_Event_Observer $observer) {
	    
 
        $product = Mage::getModel('catalog/product')
                        ->load(Mage::app()->getRequest()->getParam('product', 0));
 
        if (!$product->getId()) {
            return;
        }
 
				
		$subscriberform = Mage::app()->getRequest()->getParam('subscriber');
		
		$subscriber_firstname = $subscriberform['subscriber_firstname'];
		$subscriber_lastname = $subscriberform['subscriber_lastname'];
		$subscriber_address = $subscriberform['subscriber_address'];
		$subscriber_address2 = $subscriberform['subscriber_address2'];
		$subscriber_city = $subscriberform['subscriber_city'];
		$subscriber_state = $subscriberform['subscriber_state'];
		$subscriber_zip = $subscriberform['subscriber_zip'];
		$subscriber_email = $subscriberform['subscriber_email'];
		$subscriber_phone = $subscriberform['subscriber_phone'];
		
		#Mage::log($product->getId(), null, 'addtocart-testing.log');
		#Mage::log($subscriber_firstname, null, 'addtocart-testing.log');
		
		

		


			$lastprodid=Mage::getSingleton('checkout/session')->getLastAddedProductId(true);

			#Mage::log("lastprodid ".$lastprodid, null, 'addtocart-testing.log');
			
					
			$items = Mage::getSingleton('checkout/session')->getQuote()->getAllVisibleItems();
        $max = 0;
        $lastItem = null;
        $lastItemId = null;
        foreach ($items as $item){
	        

	        
            if ($item->getId() > $max) {
                $max = $item->getId();
                $lastItem = $item;
                $lastItemId = $item->getId();
            }
        }
			
			#Mage::log("lastItemId ".$lastItemId, null, 'addtocart-testing.log');

					#this is used for a new product only
			$quote_item_id = $lastItemId;
	  #$id = $this->getRequest()->getParam('id');
	  
	  #this is used to edit a product only...
	  	$id = Mage::app()->getRequest()->getParam('id');
	  
			#Mage::log("quote_item_id: ".$quote_item_id, null, 'addtocart-testing.log');
			#Mage::log("id: ".$id, null, 'addtocart-testing.log');
			
			
if ($id) {
	
	$quote_item_id = $id;
	
}

if ($quote_item_id && $subscriber_firstname) {
	
		
		$model = Mage::getModel('subscriptioninfo/quotes');
try {
    $model->setQuote_item_id($quote_item_id)->delete();
    #echo "Data deleted successfully.";
			#Mage::log("Data deleted successfully. ID:  ".$quote_item_id, null, 'addtocart-testing.log');
	} catch (Exception $e){
    	#echo $e->getMessage(); 
			Mage::log("exception ".$e->getMessage(), null, 'addtocart-testing.log');
	}
	
		#$whenentered = date("Ymd");
		
						$utcdate = gmdate("Y-m-d", strtotime(date("Y-m-d")));


		$data = array(
			'quote_item_id'=>$quote_item_id,
			'subscriber_firstname'=>$subscriber_firstname,
			'subscriber_lastname'=>$subscriber_lastname,
			'subscriber_address'=>$subscriber_address,
			'subscriber_address2'=>$subscriber_address2,
			'subscriber_city'=>$subscriber_city,
			'subscriber_state'=>$subscriber_state,
			'subscriber_zip'=>$subscriber_zip,
			'subscriber_email'=>$subscriber_email,
			'subscriber_phone'=>$subscriber_phone,
			'whenentered'=>$utcdate
			);
		
		$model = Mage::getModel('subscriptioninfo/quotes')->setData($data);
		try {
			$insertId = $model->save()->getId();
			#Mage::log("Data successfully inserted. Insert ID:  ".$insertId, null, 'addtocart-testing.log');
		} catch (Exception $e){
			Mage::log("exception ".$e->getMessage(), null, 'addtocart-testing.log');
		}

}

		    }
}
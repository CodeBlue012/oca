<?php
	
#this is no longer needed because I'm putting the subscriber info into the DB in the Paymentmethod
	
class Ami_Subscriptioninfo_Model_Observersavesubscriber extends Mage_Core_Model_Abstract
{
	
	  public function testinghere(Varien_Event_Observer $observer) {
   
   
	  $order = $observer->getEvent()->getOrder();
	  $order_id = $observer->getEvent()->getOrder()->getId();
    
	  $quote = Mage::getSingleton('checkout/session')->getQuote();
	  $cartItems = $quote->getAllVisibleItems();
	  foreach ($cartItems as $item) {
	  	$productId = $item->getProductId();
	  	$itemId = $item->getId();
	  	
	  	
		Mage::log("order_id ".$order_id, null, 'finalsave-testing.log');
		Mage::log("productId ".$productId, null, 'finalsave-testing.log');
		Mage::log("itemId ".$itemId, null, 'finalsave-testing.log');
		

		$collection = Mage::getModel('subscriptioninfo/quotes')->getCollection()
			->addFieldToFilter('quote_item_id', $itemId);
			foreach ($collection as $data) {
				//do something with $item
				


				$subscriber_firstname = $data->getData('subscriber_firstname');
				$subscriber_lastname = $data->getData('subscriber_lastname');
				$subscriber_address = $data->getData('subscriber_address');
				$subscriber_address2 = $data->getData('subscriber_address2');
				$subscriber_city = $data->getData('subscriber_city');
				$subscriber_state = $data->getData('subscriber_state');
				$subscriber_zip = $data->getData('subscriber_zip');
				$subscriber_email = $data->getData('subscriber_email');
				$subscriber_phone = $data->getData('subscriber_phone');
				
				if ($subscriber_firstname) { 
				
				Mage::log("subscriber_firstname ".$subscriber_firstname, null, 'finalsave-testing.log');
				
				$whenentered = date("Ymd");

		$insert_data = array(
			'quote_item_id'=>$itemId,
			'subscriber_firstname'=>$subscriber_firstname,
			'subscriber_lastname'=>$subscriber_lastname,
			'subscriber_address'=>$subscriber_address,
			'subscriber_address2'=>$subscriber_address2,
			'subscriber_city'=>$subscriber_city,
			'subscriber_state'=>$subscriber_state,
			'subscriber_zip'=>$subscriber_zip,
			'subscriber_email'=>$subscriber_email,
			'subscriber_phone'=>$subscriber_phone,
			'whenentered'=>$whenentered
			);
		
		$model = Mage::getModel('subscriptioninfo/orders')->setData($insert_data);
		try {
			$insertId = $model->save()->getId();
			Mage::log("Data successfully inserted. Insert ID:  ".$insertId, null, 'finalsave-testing.log');
		} catch (Exception $e){
			Mage::log("exception ".$e->getMessage(), null, 'finalsave-testing.log');
		}

				}
				}



		}
    
 
    }
    
    
}
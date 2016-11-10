<?php
	
	class Ami_Subscriptioninfo_Block_Product_View extends Mage_Catalog_Block_Product_View
{
 public function pullSubscriberinfo() {
	  
 $data = null;
	  $id = $this->getRequest()->getParam('id');
	  

	  		#Mage::log($id, null, 'editcart-testing.log');

	  

	  $collection = Mage::getModel('subscriptioninfo/quotes')->getCollection()
			->addFieldToFilter('quote_item_id', $id);
			

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

				}

				#$data = $collection[0];

			#$var_info = print_r($collection,true);
			
		return($data);


	#return("id ".$id);

	  
  }
}
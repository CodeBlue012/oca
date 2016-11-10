<?php
	
	class Amid_Prefillpromo_Model_Observer {
		
		const COOKIE_KEY_SOURCE = 'amid_prefillpromo';
		
    	public function captureReferral(Varien_Event_Observer $observer) {
        	$frontController = $observer->getEvent()->getFront();
        	$promocode = $frontController->getRequest()->getParam('promo', false);
			if ($promocode) {
            	Mage::getModel('core/cookie')->set(
                	self::COOKIE_KEY_SOURCE,
					$promocode,
					$this->_getCookieLifetime()
					);
				#Mage::log("set promocode ".$promocode, null, 'promocode-testing.log');
        		}
    		}
    		
    		
		protected function _getCookieLifetime() {
		 	$days = 30;
		 	// convert to seconds
		 	return (int)86400 * $days;
    		}
    		
    		
		public function populatePromo(Varien_Event_Observer $observer) {			
			$promocode = Mage::getModel('core/cookie')->get(
            	Amid_Prefillpromo_Model_Observer::COOKIE_KEY_SOURCE
            	);
				Mage::getSingleton('checkout/cart')->getQuote()->setCouponCode($promocode)->save();
				#Mage::log("read promocode cookie ".$promocode, null, 'promocode-testing.log');
    		}
    		
		}
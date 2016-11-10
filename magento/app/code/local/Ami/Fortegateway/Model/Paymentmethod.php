<?php
/*
		changing live to test and vice versa requires three file changes:
		connect-forte-classes.php
		payment-sedona-classes.php
		Paymentmethod.php	
		*/
		 
	/*
	to get last 10 customer https://onecallalertapibeta.sedonaoffice.com/api/customer
	another example https://onecallalertapibeta.sedonaoffice.com/api/customerinvoice/5084
	example values:
	$sedona_systemitemcode = Landline Mon
	$sedona_systempartcode = CTC-1041
	*/

class Ami_Fortegateway_Model_Paymentmethod extends Mage_Payment_Model_Method_Abstract {
	protected $_code  = 'fortegateway';
	protected $_formBlockType = 'fortegateway/form_fortegateway';
	protected $_infoBlockType = 'fortegateway/info_fortegateway';
	protected $_isGateway = true;
	protected $_canCapture = true;
	protected $_canAuthorize = true;
	protected $_canUseInternal = true;
  
	private function callApi(Varien_Object $payment, $amount, $type) {

 
		$api_username = $this->getConfigData('api_username');
  		$api_password = $this->getConfigData('api_password');
  		$order = $payment->getOrder();
  		
  	
  		
  		
  		
  		$types = Mage::getSingleton('payment/config')->getCcTypes();
		if (isset($types[$payment->getCcType()])) {
			$type = $types[$payment->getCcType()];
        	}
        			
		$billingaddress = $order->getBillingAddress();
		$shippingaddress = $order->getShippingAddress();
		$totals = number_format($amount, 2, '.', '');
		$orderIncrementId = $order->getIncrementId();
		$orderId = $order->getId();
		$shipping_amount = $order->getShippingAmount();
		$shipping_amount = number_format($shipping_amount, 2, '.', '');
		
		#Mage::log("shipping_amount ".$shipping_amount, null, $this->getCode().'-tsting.log');
		#Mage::log("orderIncrementId ".$orderIncrementId.", orderId ".$orderId, null, $this->getCode().'-tsting.log');
		
		$currencyDesc = $order->getBaseCurrencyCode();
 
		$url = $this->getConfigData('gateway_url');
        
		$billing_firstname = $billingaddress->getData('firstname');
		$billing_lastname = $billingaddress->getData('lastname');
		$billing_phone = $billingaddress->getData('telephone');
		$billing_email = $billingaddress->getData('email');
		$billing_ipaddress = $_SERVER['REMOTE_ADDR'];
		$billing_address = $billingaddress->getData('street');
		$billing_address2 = ""; 
		$billing_city = $billingaddress->getData('city');
		$billing_country = $billingaddress->getData('country_id');
		$billing_state = $billingaddress->getData('region');
		$billing_zip = $billingaddress->getData('postcode');
		$shipping_firstname = $shippingaddress->getData('firstname');
		$shipping_lastname = $shippingaddress->getData('lastname');
		$shipping_phone = $shippingaddress->getData('telephone');
		$shipping_email = $shippingaddress->getData('email');
		$shipping_address = $shippingaddress->getData('street');
		$shipping_city = $shippingaddress->getData('city');
		$shipping_address2 = ""; 
		$shipping_country = $shippingaddress->getData('country_id');
		$shipping_state = $shippingaddress->getData('region');
		$shipping_zip = $shippingaddress->getData('postcode');
		$card_expmonth = $payment->getCcExpMonth();
		$card_expyear = $payment->getCcExpYear();
		$card_cardnumber = $payment->getCcNumber();
		$billing_cc_type = strtoupper($type);
		$card_securitycode = $payment->getCcCid();
		$merchant_ref_number = $orderIncrementId;
		$currencydesc = $currencyDesc;
		$paymentAmount = $totals;
		$billing_countrycode = "US";


		#$card_cardnumber = "4111111111111111";
		$card_cardnumber = trim($card_cardnumber);
		
		
		#Mage::log("-----", null, 'erase-this.log');
		#Mage::log("last name $billing_lastname $card_cardnumber | $card_expmonth / $card_expyear | $card_securitycode", null, 'erase-this.log');
	
	
		$cardfirstdigit = substr($card_cardnumber,0,1);
		
		$totalchargeformatted = $paymentAmount; 
		$totalchargerecformatted = ""; 
		$authnetnumber = ""; 
		$response_code_arr = ""; 
		$soapClientToken = ""; 
		$soapPaymentToken = ""; 
		$answerMessage = ""; 
		$answerError = ""; 
		$errorMsg = ""; 
		$lastnamesub = $billing_lastname;
		$lastnamesub = preg_replace("/[^a-z]/i","",$lastnamesub);
		$lastnamesub = strtoupper(substr($lastnamesub,0,3));
		$paymentAmountRecurring = $totalchargerecformatted;

		
		/*
		pg_schedule_frequency
			Val	Frequency				Period
			10	Weekly					Every 7 days
			15	Bi-Weekly				Every 14 days
			20	Same day every month	Monthly
			25	Bi-Monthly				Every 2 months
			30	Quarterly				Every 3 months
			35 	Semi- Annually			Twice a year
			40	Yearly					Once a year
		*/
		
		
		/* accuracy of forte soap call may not matter since we are just needing a token for sedona to do recurring charging */
		
	
/*
		$useplan = 2;
		if ($useplan == 1) {
			$pg_schedule_frequency = "40";
			$billingfrequency = 12;
			$frequency = "annually";
			}
		if ($useplan == 2) {
			$pg_schedule_frequency = "30";
			$billingfrequency = 3;
			$frequency = "quarterly";
			}
		if ($useplan == 3) {
			$pg_schedule_frequency = "20";
			$billingfrequency = 1;
			$frequency = "monthly";
			}
*/


		$pg_schedule_frequency = "20";
		$billingfrequency = 1;
		$frequency = "monthly";
		$td = date("d");
		$tm = date("m");
		$ty = date("Y");

		$startDate = date("m/d/Y",mktime(0,0,0,$tm+$billingfrequency,$td,$ty));


		#Mage::log("paymentAmount $paymentAmount paymentAmountRecurring $paymentAmountRecurring startDate $startDate", null, 'forte-errors.log');

	
		/*
		VISA VISA
		MAST Master Card
		AMER American Express
		DISC Discover Card
		DINE Diner’s Club
		JCB	JCB
		
		AMEX starts with a 3
		VISA starts with a 4
		MC starts with a 5
		DISCOVER starts with a 6
		*/
	
		$cardType_arr[3] = "AMER"; #Amex
		$cardType_arr[4] = "VISA"; #Visa
		$cardType_arr[5] = "MAST"; #MC
		$cardType_arr[6] = "DISC"; #DISCOVER
		$cardType = $cardType_arr[$cardfirstdigit];
		$ForteCardType = $cardType;
		
		$ecom_payment_card_type = $cardType_arr[$cardfirstdigit];
	
		if ($card_expmonth < 10) { $card_expmonth = "0".$card_expmonth; }
	
		$card_expiration = $card_expyear.$card_expmonth;
		

		#### SOAP call
		
		/*
		change this in connect-forte-classes.php
		Live:
			API Login ID: Th2OnRXr3h
			Secure Transaction Key: V8WRxkyAjCwFnx
		Sandbox:
        	API Login ID: fkavCo3AK         
			Secure Transaction Key: hBEPDDK2lf7yby
		*/

		$forteSoap = new Achdirect_cmi;
		$forteSoap->FirstName = $billing_firstname;
		$forteSoap->LastName = $billing_lastname;
		$forteSoap->payment_arr['AcctHolderName'] = $billing_firstname." ".$billing_lastname;
		$forteSoap->payment_arr['CcCardNumber'] = $card_cardnumber;
		$forteSoap->payment_arr['CcExpirationDate'] = $card_expyear.$card_expmonth;
		$forteSoap->payment_arr['CcCardType'] = $ForteCardType;

		$val = $forteSoap->createClient();
		$soapClientToken = $forteSoap->soapClientToken;
		$val = $forteSoap->createPaymentMethod();
		$soapPaymentToken = $forteSoap->soapPaymentToken;
		$showsoaperror = $forteSoap->showsoaperror;
		
		/*
		Mage::log("soapClientToken $soapClientToken", null, 'forte-errors.log');
		Mage::log("soapPaymentToken $soapPaymentToken", null, 'forte-errors.log');
		Mage::log("showsoaperror $showsoaperror", null, 'forte-errors.log');
		*/

		$authnetnumber = "W".$orderIncrementId;

		$pg_consumer_id = $authnetnumber;
		$pg_client_id = $soapClientToken;
		$pg_payment_method_id = $soapPaymentToken;


		### AGI call
		#test password this is in two spots this is 1/2
			$fullrequest = "pg_password=61UtL8Yb";
		#live password
			#$fullrequest = "pg_password=vm3C4O3n8C";
		$fullrequest .= "&pg_merchant_id=172093";
		$fullrequest .= "&pg_transaction_type=10";
		$fullrequest .= "&pg_merchant_data_1=One Call Product Data";
		$fullrequest .= "&pg_client_id=$pg_client_id";
		$fullrequest .= "&pg_payment_method_id=$pg_payment_method_id";
		$fullrequest .= "&pg_consumer_id=$pg_consumer_id";
		$fullrequest .= "&ecom_billto_postal_name_first=$billing_firstname";
		$fullrequest .= "&ecom_billto_postal_name_last=$billing_lastname";
		$fullrequest .= "&ecom_billto_postal_street_line1=$billing_address";
		$fullrequest .= "&ecom_billto_postal_street_line2=$billing_address2";
		$fullrequest .= "&ecom_billto_postal_city=$billing_city";
		$fullrequest .= "&ecom_billto_postal_stateprov=$billing_state";
		$fullrequest .= "&ecom_billto_postal_postalcode=$billing_zip";
		$fullrequest .= "&ecom_billto_postal_countrycode=$billing_countrycode";
		$fullrequest .= "&ecom_billto_telecom_phone_number=$billing_phone";
		$fullrequest .= "&ecom_billto_online_email=$billing_email";
		$fullrequest .= "&pg_entered_by=website";
		$fullrequest .= "&pg_total_amount=$paymentAmount";
		#$fullrequest .= "&pg_schedule_quantity=1";
		#$fullrequest .= "&pg_schedule_frequency=$pg_schedule_frequency";
		#$fullrequest .= "&pg_schedule_recurring_amount=0";
		#$fullrequest .= "&pg_schedule_recurring_amount=$paymentAmountRecurring";
		#$fullrequest .= "&pg_schedule_start_date=$startDate";
		$fullrequest .= "&ecom_payment_card_type=$ecom_payment_card_type";
		$fullrequest .= "&ecom_payment_card_name=$billing_firstname $billing_lastname";
		$fullrequest .= "&ecom_payment_card_number=$card_cardnumber";
		$fullrequest .= "&ecom_payment_card_expdate_month=$card_expmonth";
		$fullrequest .= "&ecom_payment_card_expdate_year=$card_expyear";
		$fullrequest .= "&ecom_payment_card_verification=$card_securitycode";
		/*
		$fullrequest .= "&pg_procurement_card=xxx";
		$fullrequest .= "&pg_customer_acct_code=xxx";
		$fullrequest .= "&pg_cc_swipe_data=xxx";
		$fullrequest .= "&pg_cc_enc_swipe_data=xxx";
		$fullrequest .= "&pg_cc_enc_decryptor=xxx";
		$fullrequest .= "&ecom_3d_secure_data=xxx";
		$fullrequest .= "&ecom_3d_secure_authenticated=xxx";
		$fullrequest .= "&pg_partial_auth_allowed_flag=xxx";
		$fullrequest .= "&pg_mail_or_phone_order=xxx";
		$fullrequest .= "&pg_line_item_header=SKU,Price,Qty=xxx";
		$fullrequest .= "&pg_line_item_1=021000021,45.00,2";
		$fullrequest .= "&pg_billto_ssn=xxx";
		$fullrequest .= "&pg_billto_dl_number=xxx";
		$fullrequest .= "&pg_billto_dl_state=xxx";
		$fullrequest .= "&pg_billto_date_of_birth=xxx";
		$fullrequest .= "&pg_customer_token=$pg_customer_token";
		$fullrequest .= "&pg_payment_token=$pg_payment_token";
		$fullrequest .= "&pg_onetime_token=xxx";
		$fullrequest .= "&pg_billto_postal_name_company=xxx";
		$fullrequest .= "&pg_sales_tax_amount=xxx";
		*/
			
		#test url use tst login and tst tran key above to run tsts - this is in two spots this is 2/2
			$url = "https://www.paymentsgateway.net/cgi-bin/posttest.pl"; 
		#live prodcution url
			#$url = "https://www.paymentsgateway.net/cgi-bin/postauth.pl";
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fullrequest);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		
		
		$theresult = curl_exec($ch);
		curl_close($ch);
		
		#Mage::log($theresult, null, $this->getCode().'-tsting.log');		
		unset($resexpl);
		$resexpl = explode("\n",$theresult);
		foreach ($resexpl as $key => $val) {
			#Mage::log("$key is $val", null, $this->getCode().'-tsting.log');
			if (preg_match("/=/",$val)) {
				$intoarray = explode("=",$val);
				$keylabel = $intoarray[0];
				$keyvalue = $intoarray[1];
				if ($keylabel) {
					$answer[$keylabel] = $keyvalue;
					}
				}			
			}
				
		$response_type_arr["A"] = "APPROVED";
		$response_type_arr["D"] = "DECLINED";
		$response_type_arr["E"] = "ERROR OCCURED";

		#$answer['pg_authorization_code'] = "not avail";
		#$answer['pg_trace_number'] = "not avail";
		
		$response_flat = json_encode($answer);
		
		if (!preg_match("/pg_authorization_code/i",$response_flat)) {
			$answer['pg_authorization_code'] = "";
			$out = print_r($answer,true);
			Mage::log($out, null, 'forte-errors.log');
			
			
			
			}
		
		$pg_authorization_code = $answer['pg_authorization_code'];	
		$pg_trace_number = $answer['pg_trace_number'];
			
		$authcode = $pg_authorization_code;
		
		$badcard = true;
		$cardapproved = false;
		
		include_once("connect-forte-codes.php");
		
		//tst to run thru order data:
		
		$orderinfo = "";
		
			
		#$visibleItems = $quote->getAllVisibleItems(); // from cart
		$visibleItems = $order->getAllVisibleItems(); // from order
		$SystemCode = "";
		$sedonacodes = "";
		$sedonamonitoring = "";
	  
		foreach ($visibleItems as $visibleItem) {
			$visibleProductId = $visibleItem->getProductId();
		  	$visibleItemId = $visibleItem->getId();
		  	$visibleItemSku = $visibleItem->getSku();		  	
		  	#Mage::log("visibleItemId ".$visibleItemId.", visibleProductId ".$visibleProductId.", visibleItemSku ".$visibleItemSku, null, $this->getCode().'-tsting.log');
			$visibleProduct = Mage::getModel('catalog/product')->load($visibleProductId);
			if($visibleProduct->getTypeId()==='bundle') {
				$options = $visibleProduct->getProductOptionsCollection();
				#$out = print_r($options,true);
				foreach($options as $option) {
					$sku = $option->getSku();
					#Mage::log("sku: ".$sku, null, $this->getCode().'-tsting.log');
					}
				#this code gets subscriber info form the form
				$childProducts = $visibleItem->getProduct()->getTypeInstance(true)->getOrderOptions($visibleItem->getProduct());
				$out = print_r($childProducts,true);
				#Mage::log("childProducts: ".$out, null, $this->getCode().'-tsting.log');

				$subscriptiondata = print_r($childProducts['info_buyRequest']['subscriber'],true);
				$subscriberdata = $childProducts['info_buyRequest']['subscriber'];
				
				if ($subscriberdata) {
					$sedonaproductdata[$visibleItemId]['subscriber'] = $subscriberdata;
					}
				}
			}
			

			$orderItems = $order->getItemsCollection();
			foreach ($orderItems as $item){
				$optioninfo = "";  
				$itemId = $item->getId();
				$product_id = $item->product_id;
				$product_sku = $item->sku;
				$product_price = $item->getPrice();
				$product_name = $item->getName();
				$parent_item_id = $item->getParentItemId();
				
				$itemProduct = Mage::getModel('catalog/product')->load($product_id);
				$parent_product_id = $itemProduct->getParentProductId();
				$sedonacodes = $itemProduct->getSedonacodes();
				$sedonamonitoring = $itemProduct->getSedonamonitoring();
				$billeveryxmonths = $itemProduct->getBilleveryxmonths();
				$initialbillingamount = $itemProduct->getInitialbillingamount();
				$systemcode = $itemProduct->getSystemcode();
				$recurringbillingamount = $itemProduct->getRecurringbillingamount();
				$startrecbillinxmonths = $itemProduct->getStartrecbillinxmonths();
				$systemcode = $itemProduct->getSystemcode();
				
				$showinfo[] = "itemID $itemId, parent_item_id $parent_item_id, parent_product_id $parent_product_id, product_id $product_id, product_sku $product_sku, product_price $product_price, product_name $product_name, sedonacodes $sedonacodes";	
				
				if (!$parent_item_id) {
					$sedonaproductdata[$itemId]['product_id'] = $product_id;
					$sedonaproductdata[$itemId]['product_sku'] = $product_sku;
					$sedonaproductdata[$itemId]['product_price'] = $product_price;
					$sedonaproductdata[$itemId]['product_name'] = $product_name;
					}
				else if ($sedonamonitoring) {
					
					$initialbillingamount = number_format($initialbillingamount,2);
					$recurringbillingamount = number_format($recurringbillingamount,2);
					
					$sedonaproductdata[$parent_item_id]['monitoring']['product_name'] = $product_name;
					$sedonaproductdata[$parent_item_id]['monitoring']['itemId'] = $itemId;
					$sedonaproductdata[$parent_item_id]['monitoring']['product_id'] = $product_id;
					$sedonaproductdata[$parent_item_id]['monitoring']['product_sku'] = $product_sku;
					$sedonaproductdata[$parent_item_id]['monitoring']['sedonamonitoring'] = $sedonamonitoring;
					$sedonaproductdata[$parent_item_id]['monitoring']['systemcode'] = $systemcode;
					$sedonaproductdata[$parent_item_id]['monitoring']['billeveryxmonths'] = $billeveryxmonths;
					$sedonaproductdata[$parent_item_id]['monitoring']['startrecbillinxmonths'] = $startrecbillinxmonths;
					$sedonaproductdata[$parent_item_id]['monitoring']['initialbillingamount'] = $initialbillingamount;
					$sedonaproductdata[$parent_item_id]['monitoring']['recurringbillingamount'] = $recurringbillingamount;
					$sedonaproductdata[$parent_item_id]['monitoring']['product_price'] = $product_price;
					}
				else {
					
					$product_price = number_format($product_price,2);
					
					$sedonaproductdata[$parent_item_id]['children'][$itemId]['product_name'] = $product_name;
					$sedonaproductdata[$parent_item_id]['children'][$itemId]['product_id'] = $product_id;
					$sedonaproductdata[$parent_item_id]['children'][$itemId]['product_sku'] = $product_sku;
					$sedonaproductdata[$parent_item_id]['children'][$itemId]['sedonacodes'] = $sedonacodes;
					$sedonaproductdata[$parent_item_id]['children'][$itemId]['product_price'] = $product_price;
										
					#$sedonaproductdata[$itemId]['children'] = $sedonaproductdata[$parent_item_id]['children'][$itemId];
					}
					
									
				}
				
				
/*
				foreach ($sedonaproductdata as $parent_item_id => $parent_product_id) {
					foreach ($savedchild[$parent_item_id] as $child_item_id => $child_product_id) {
												
					}
				}
*/
				
				$showinfoshow = print_r($sedonaproductdata, true);
				#Mage::log("sedonaproductdata ".$showinfoshow, null, $this->getCode().'-tsting.log');
				
				
				



						
			
		
			if ($answer['pg_response_type'] == "A") {
				$badcard = false;
				$cardapproved = true;
				}
			else {
				$badcard= true;
				$cardapproved = false;
				$answerError = $answer['pg_response_type'];
				$answerMessage = $response_code_arr[$answer['pg_response_code']];
				$answerMessage .= " ".$answer['pg_response_description'];
				$answerMessage .= " (Ref #".$answer['pg_response_code'].")";
				}
		
			################## credit card approval end ########################
		
		
			if ($cardapproved == false) {			
				$body_post = "";
				foreach ($answer as $key => $val) {
				if ($key == "ecom_payment_card_number") { $val = "xxxxxxxx"; }
					$body_post .= "POST: $key = $val\n";
					}
		
				foreach ($_SERVER as $key => $val) {
					$body_post .= "SERVER: $key = $val\n";
					}
		
				$subject = "$billing_firstname $billing_lastname Forte card tracking";
				$body = "This email is for Forte temporary testing purposes only.\n";
				$body.= "$authnetnumber for $billing_firstname $billing_lastname\n";
				$body.= "answerError: $answerError\n";
				$body.= "answerMessage: $answerMessage\n";
				
				$body.= "\n";
				$body.= "\n";
				$body.= "\n";
				$body.= "\n";
				$body.= $body_post;
				$body.= "\n";
				$body.= "\n";
				$body.= "\n";
				$body.= "\n";
				#$mailto = "frances@americanmedical-id.com";
				#$mailto = "anthonyb@icloud.com";
				$mailto = "aplatt@identifyyourself.com";
				$mailfrom = "FROM: webmaster@onecallmedicalalert.com\n";
				$mailfrom .= "CC: info@onecallmedicalalert.com,lgabel@identifyyourself.com\n";
				$mailfrom .= "BCC: anthonyb@icloud.com\n";
				#$mailfrom .= "CC: aplatt@identifyyourself.com\n";
				/* turn back on */
				mail($mailto, $subject, $body, $mailfrom);
				Mage::log($body, null, 'forte-errors.log');
				}
				
			$status = 0;
		    if ($cardapproved) {
		        $status = 1;
		       
		        $sedona = new class_sedona;
		        $sedona->sedonaproductdata = $sedonaproductdata;
		      
				$sedona->ForteCardType = $ForteCardType;
				$sedona->card_expmonth = $card_expmonth;
				$sedona->card_expyear = $card_expyear;
				$sedona->card_cardnumber = $card_cardnumber;
				$sedona->pg_payment_method_id = $pg_payment_method_id;
				$sedona->authnetnumber = $authnetnumber;
				$sedona->authcode = $authcode;
				$sedona->orderId = $orderId;
				$sedona->orderIncrementId = $orderIncrementId;
				$sedona->shipping_amount = $shipping_amount;
				
				$sedona->pg_consumer_id = $pg_consumer_id;
				$sedona->billing_firstname = $billing_firstname;
				$sedona->billing_lastname = $billing_lastname;
				$sedona->billing_address = $billing_address;
				$sedona->billing_address2 = $billing_address2;
				$sedona->billing_city = $billing_city;
				$sedona->billing_state = $billing_state;
				$sedona->billing_zip = $billing_zip;
				$sedona->billing_phone = $billing_phone;
				$sedona->billing_email = $billing_email;
				$sedona->shipping_firstname = $shipping_firstname;
				$sedona->shipping_lastname = $shipping_lastname;
				$sedona->shipping_address = $shipping_address;
				$sedona->shipping_address2 = $shipping_address2;
				$sedona->shipping_city = $shipping_city;
				$sedona->shipping_state = $shipping_state;
				$sedona->shipping_zip = $shipping_zip;
				$sedona->shipping_phone = $shipping_phone;
				$sedona->shipping_email = $shipping_email;
				$sedona->f_sedona();
				$sedonaerror = $sedona->sedonaerror;
		    	}
		    	
		    $transaction_id = $pg_authorization_code;
		    $fraud = true;
		    $fraud = false;
			$forteresponse = $answerError." ".$answerMessage;
		
			#return array('status'=>$status,'transaction_id' => $transaction_id , 'fraud' => $fraud, 'forteresponse' => $forteresponse, 'sedonaerror' => $sedonaerror);
			return array('status'=>$status,'transaction_id' => $transaction_id , 'fraud' => $fraud, 'forteresponse' => $forteresponse);
	}
		  
	public function capture(Varien_Object $payment, $amount) {
		$errorMsg = "";
		$order = $payment->getOrder();
		$result = $this->callApi($payment,$amount,'authorize');
		if ($result === false) {
			$errorCode = 'Invalid Data';
			#this is the pop up if an error occurs #1
		    $errorMsg = $this->_getHelper()->__('Error Processing the request');
		    } 
		else {
			#Mage::log($result, null, $this->getCode().'.log');
			
		    //process result here to check status etc as per payment gateway.
		    // if invalid status throw exception
		 
		    if ($result['status'] == 1) {
				$payment->setTransactionId($result['transaction_id']);
		        $payment->setIsTransactionClosed(1);
		        $payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, array('key1'=>'value1', 'key2'=>'value2'));
				}
			else {
				#this is the pop up if an error occurs #2
				$errorMsg = $result['forteresponse'];
				Mage::throwException($errorMsg);
				}
			// Add the comment and save the order
		    }
		if ($errorMsg) {
		    Mage::throwException($errorMsg);
		    } 
		return $this;
		}


/*
public function authorize(Varien_Object $payment, $amount)
    {
        $order = $payment->getOrder();
        $result = $this->callApi($payment,$amount,'authorize');
        if($result === false) {
            $errorCode = 'Invalid Data';
            $errorMsg = $this->_getHelper()->__('Error Processing the request');
        } else {
            Mage::log($result, null, $this->getCode().'.log');
            //process result here to check status etc as per payment gateway.
            // if invalid status throw exception
 
            if($result['status'] == 1){
                $payment->setTransactionId($result['transaction_id']);
                $payment->setIsTransactionClosed(0);
                $payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS,array('key1'=>'value1','key2'=>'value2')); //use this in case you want to add some extra information
            }else{
                Mage::throwException($errorMsg);
            }
 
            // Add the comment and save the order
        }
        if($errorMsg){
            Mage::throwException($errorMsg);
        }
 
        return $this;
    }
*/
 
 /*
	protected $_canRefund               = true;
public function processBeforeRefund($invoice, $payment){} //before refund
public function refund(Varien_Object $payment, $amount){} //refund api
public function processCreditmemo($creditmemo, $payment){} //after refund


public function refund(Varien_Object $payment, $amount){
        $order = $payment->getOrder();
        $result = $this->callApi($payment,$amount,'refund');
        if($result === false) {
            $errorCode = 'Invalid Data';
            $errorMsg = $this->_getHelper()->__('Error Processing the request');
            Mage::throwException($errorMsg);
        }
        return $this;
 
    }
*/

 	}


include_once("connect-forte-classes.php");
include_once("connect-sedona-classes.php");
include_once("payment-sedona-classes.php");
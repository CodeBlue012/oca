<?php

/*
		changing live to test and vice versa requires three file changes:
		connect-forte-classes.php
		payment-sedona-classes.php
		Paymentmethod.php	
		*/
		
			
	class class_sedona {

function convert_multi_array($array) {
  	$out = print_r($array,true);
  	$this->sedonaerror = $out;
  	#mail("weberrors@onecallalert.com","OCA Sedona Error",$out,"from:weberrors@onecallalert.com\ncc:anthonyb@icloud.com\n");
	Mage::log($out, null, 'sedona-errors.log');
	}
	
function save_array_data($array) {
	
	$dateattach = date("-m-d");	
  	$out = print_r($array,true);
	Mage::log($out, null, 'z_sedona-data'.$dateattach.'.log');
	$out_json = json_encode($array);
	Mage::log($out_json, null, 'z_sedona-data-json'.$dateattach.'.log');
	}

function f_sedona() {
	
$doCustomer= true;
$doCustomerBill= true;
$doCustomerSite= true;
$doCustomerSystem= true;
$doCustomerRecurring= true;
$doCustomerCC= true;
$doCustomerInvoice= true;
$doCustomerPayment= true;
$doCustomerContact= true;

$this->sedonaerror = "";

$today_m = date("m");
$today_d = date("d");
$today_y = date("Y");

$shipping_amount = 0;
$product_price_total = 0;


$sedonaproductdata = $this->sedonaproductdata;
$ForteCardType = $this->ForteCardType;
$card_expmonth = $this->card_expmonth;
$card_expyear = $this->card_expyear;
$card_cardnumber = $this->card_cardnumber;
$pg_payment_method_id = $this->pg_payment_method_id;
$authnetnumber = $this->authnetnumber;
$authcode = $this->authcode;
$pg_consumer_id = $this->pg_consumer_id;
$billing_firstname = $this->billing_firstname;
$billing_lastname = $this->billing_lastname;
$billing_address = $this->billing_address;
$billing_address2 = $this->billing_address2;
$billing_city = $this->billing_city;
$billing_state = $this->billing_state;
$billing_zip = $this->billing_zip;
$billing_phone = $this->billing_phone;
$billing_email = $this->billing_email;
$shipping_firstname = $this->shipping_firstname;
$shipping_lastname = $this->shipping_lastname;
$shipping_address = $this->shipping_address;
$shipping_address2 = $this->shipping_address2;
$shipping_city = $this->shipping_city;
$shipping_state = $this->shipping_state;
$shipping_zip = $this->shipping_zip;
$shipping_phone = $this->shipping_phone;
$shipping_email = $this->shipping_email;
$orderId = $this->orderId;
$orderIncrementId = $this->orderIncrementId;
$shipping_amount = $this->shipping_amount;

include_once("conversion-states.php");
$billing_state_abbrev = $statename_arr[$billing_state];
$shipping_state_abbrev = $statename_arr[$shipping_state];




$promocode = "none";
#$unit = $usesystemcode." ".$useplantype;
/*
if ($usetwoway == 2) {
	$unit = $usesystemcode." ".$usetwowaytype." ".$useplantype;
}
*/
$trackingcode = "none";
$notes = "none";

#Landline Climax
#Wireless Climax
#Mobile ResCube

#Mage::log("usesystemcode $usesystemcode", null, 'sedona.log');


	#$systemcode = "Landline Climax";
	#$systemcode = $SystemCode;
	#$systemcode = "N/A";
	
	
	#$NextCycleDate = $startDate;
	#AMER,DISC, MAST, VISA
	$CardType = $ForteCardType;
	$ExpirationMonth = $card_expmonth;
	$ExpirationYear = $card_expyear;
	$ExpirationYear2 = substr($ExpirationYear,-2);
	$AccountName = "$billing_lastname, $billing_firstname";
	$AccountName = substr($AccountName,0,22);
	$CustomerAddress = $billing_address;
	$CustomerZip = $billing_zip;
	$LastFour = substr($card_cardnumber,-4);
	#$pg_payment_token from Forte
	$ACHDirectToken = $pg_payment_method_id;
	$InvoiceNumber = $authnetnumber;
	$BranchCode = $billing_state_abbrev;
	
	
	/*
	to get last 10 customer https://onecallalertapibeta.sedonaoffice.com/api/customer
	another example https://onecallalertapibeta.sedonaoffice.com/api/customerinvoice/5084
	*/




	#test URL 1/1 location
	$connection = new rest_class("https://onecallalertapibeta.sedonaoffice.com/api/", "oca_dev", "kT4vm29PPx");
	#live URL
	#$connection = new rest_class("https://onecallalertapi.sedonaoffice.com/api/", "oca_dev", "kT4vm29PPx");
	

// to turn on or off debuggin flags
#$connection->debug = true;
$connection->debug = false;

$howhear_arr[1] = "Doctor";
$howhear_arr[2] = "Nurse";
$howhear_arr[3] = "Friends or Family";
$howhear_arr[4] = "Pharmacist";
$howhear_arr[5] = "Other";

#$howhearshow = $howhear_arr[$howhear];

#$sedonanotes = "Amount: \$$totalchargeformatted\r";
#$sedonanotes .= "Plan: $unit\r";
#$sedonanotes .= "Pendant 1: $pendant1\r";
#$sedonanotes .= "Pendant 2: $pendant2\r";
#$sedonanotes .= "Case Bundle: $pendantcasebundledescr\r";
#$sedonanotes .= "GSM: $gsmshowdescr\r";
#$sedonanotes .= "Auth Code: $authcode\r";
#$sedonanotes .= "Brochure Code: $brochurecode\r";
#$sedonanotes .= "How Hear: $howhearshow\r";




#if ($analyticstracking) {
#	$sedonanotes .= $analyticstracking;
#	}

#Plan Type - ConsumerAccount.Account0724
#Tracking Code – ConsumerAccount.Account0103

/*
$CustomerNumberrand = rand(1000,9999);
$CustomerNumber = strtolower($shipping_lastname.$CustomerNumberrand);
$CustomerNumber = preg_replace("/[^a-z0-9]/i","",$CustomerNumber);
*/

#$FutureDate = date("Ymd",mktime(0,0,0,$today_m+$billingfrequency,$today_d,$today_y));
#$FutureDate = date("Y-m-d",mktime(0,0,0,$today_m-1,$today_d,$today_y));

#from forte
$CustomerNumber = $pg_consumer_id;
$CustomerNumber = "W".$orderIncrementId;

$doexit = false;

#$termcode = "N/A";
$termcode = "DOR";
$typecode = "Customer";

/*
$fullbilling = $billing_firstname.$billing_lastname.$billing_address.$billing_address2.$billing_city.$billing_state.$billing_zip.$billing_phone.$billing_email;
$fullsubscriber = $subscriber_firstname.$subscriber_lastname.$subscriber_address.$subscriber_address2.$subscriber_city.$subscriber_state.$subscriber_zip.$subscriber_phone.$subscriber_email; 

if ($fullbilling == $fullsubscriber) {
	$typecode = "Customer/Subscriber";
	}
*/

#$shipping_firstname

#change this later but Magento is passing Texas which errors out
#$BranchCode = "TX";
$CurrentDate = date("Y-m-d");
$CustomerId = "";
$CustomerBillId = "";


$linereturn = "\n-----------------------\n";

$dateattach = date("-m-d");

Mage::log($linereturn, null, 'z_sedona-data'.$dateattach.'.log');
Mage::log("\n", null, 'z_sedona-data-json'.$dateattach.'.log');

#$connection->entity = "ConsumerAccountSet";
$connection->entity = "customer";
$showresponse['CustomerId'] = "";


$Customer_arr = array(
		'CustomerNumber' => $CustomerNumber,
		'CustomerName' => "$billing_firstname $billing_lastname",
		'BranchCode' => $BranchCode,
		'CustomerSince' => $CurrentDate,
		'TypeCode' => $typecode,
		'TermCode' => $termcode,
		'PrintStatements' => false,
		'PrintSiteOnInvoices' => true
		);
		
			$this->save_array_data($Customer_arr);


$showresponse = $connection->Insert($Customer_arr);
	#$CustomerId = $connection->GetGuid();
	
	$showresponse = $connection->GetResponse();
			$this->convert_multi_array($showresponse);
	$error = $connection->GetError($showresponse);
	if ($error) { 
		Mage::log("Customer insertion error: $error", null, 'sedona-errors.log');
		}
	else {
		$CustomerId = $showresponse['CustomerId'];
		Mage::log("CustomerId: $CustomerId", null, 'sedona.log');
		}
		
	if (!$CustomerId) {
			Mage::log("Missing CustomerId", null, 'sedona.log');
			Mage::log("CustomerNumber: $CustomerNumber", null, 'sedona.log');
			Mage::log("CustomerName: $billing_firstname $billing_lastname", null, 'sedona.log');
			Mage::log("BranchCode: $BranchCode", null, 'sedona.log');
			Mage::log("CustomerSince: $CurrentDate", null, 'sedona.log');
			Mage::log("TypeCode: $typecode", null, 'sedona.log');
			Mage::log("TermCode: $termcode", null, 'sedona.log');
			Mage::log("PrintStatements: false", null, 'sedona.log');
			Mage::log("PrintSiteOnInvoices: true", null, 'sedona.log');
			$this->convert_multi_array($showresponse);
		}	

	if ($CustomerId) {
		
		
		// changing the entity to work with address
		
		$CustomerBill_arr = array(
				 'CustomerId' => $CustomerId,
				 'IsCommercial' => false,
				 'BillName' => "$billing_lastname, $billing_firstname",
				 'Address1' => $billing_address,
				 'Address2' => $billing_address2,
				 'City' => $billing_city,
				 'State' => $billing_state_abbrev,
				 'Zip' => $billing_zip,
				 'CountryAbbrev' => 'USA',
				 'Phone1' => $billing_phone,
				 'Email' => $billing_email,
				 'IsPrimary' => true,
				 'BranchCode' => $BranchCode,
				 'EmailInvoices' => false);
				 
		$this->save_array_data($CustomerBill_arr);
		
		
		if ($doCustomerBill) {
		$connection->entity = "CustomerBill";
		$connection->Insert($CustomerBill_arr);

		$showresponse = $connection->GetResponse();
		$error = $connection->GetError($showresponse);
		if ($error) { 
			Mage::log("CustomerBill insertion error: $error", null, 'sedona-errors.log');
			  }
		else {
			$CustomerBillId = $showresponse['CustomerBillId'];
			Mage::log("CustomerBillId: $CustomerBillId", null, 'sedona.log');
			}
			
		
		
		if (!$CustomerBillId) {
			Mage::log("Missing CustomerBillId", null, 'sedona.log');
			Mage::log("IsCommercial: $IsCommercial", null, 'sedona.log');
			Mage::log("BillName: $BillName", null, 'sedona.log');
			Mage::log("Address1: $Address1", null, 'sedona.log');
			Mage::log("Address2: $Address2", null, 'sedona.log');
			Mage::log("City: $City", null, 'sedona.log');
			Mage::log("State: $State", null, 'sedona.log');
			Mage::log("Zip: $Zip", null, 'sedona.log');
			Mage::log("CountryAbbrev: $CountryAbbrev", null, 'sedona.log');
			Mage::log("Phone1: $Phone1", null, 'sedona.log');
			Mage::log("Email: $Email", null, 'sedona.log');
			Mage::log("IsPrimary: $IsPrimary", null, 'sedona.log');
			Mage::log("BranchCode: $BranchCode", null, 'sedona.log');
			Mage::log("EmailInvoices: $EmailInvoices", null, 'sedona.log');
			$this->convert_multi_array($showresponse);
			}
			}
		
		}
		
		
		
	


$showinfo = print_r($sedonaproductdata, true);
#Mage::log("sedonaproductdata: ".$showinfo, null, 'sedona.log');

foreach ($sedonaproductdata as $productkey => $sedonarecord) {
	
	unset($CustomerSiteId);
	unset($CustomerSystemId);
	unset($CustomerRecurringId);
	unset($CustomerCCId);
	unset($InvoiceId);
	unset($DepositCheckId);
	
	$CustomerCCId = "";
	
	$sedonasubscriber = $sedonarecord['subscriber'];
	$subscriber_firstname = $sedonasubscriber['subscriber_firstname'];
	$subscriber_lastname = $sedonasubscriber['subscriber_lastname'];
	$subscriber_address = $sedonasubscriber['subscriber_address'];
	$subscriber_address2 = $sedonasubscriber['subscriber_address2'];
	$subscriber_city = $sedonasubscriber['subscriber_city'];
	$subscriber_state = $sedonasubscriber['subscriber_state'];
	$subscriber_zip = $sedonasubscriber['subscriber_zip'];
	$subscriber_phone = $sedonasubscriber['subscriber_phone'];
	$subscriber_email = $sedonasubscriber['subscriber_email'];
	
	
		if ($subscriber_firstname) { 
				
				Mage::log("-----", null, 'erase-this.log');
				Mage::log("subscriber_firstname ".$subscriber_firstname, null, 'erase-this.log');
				Mage::log("subscriber_lastname ".$subscriber_lastname, null, 'erase-this.log');
				Mage::log("subscriber_address ".$subscriber_address, null, 'erase-this.log');
				Mage::log("subscriber_address2 ".$subscriber_address2, null, 'erase-this.log');
				Mage::log("subscriber_city ".$subscriber_city, null, 'erase-this.log');
				Mage::log("subscriber_state ".$subscriber_state, null, 'erase-this.log');
				Mage::log("subscriber_zip ".$subscriber_zip, null, 'erase-this.log');
				Mage::log("subscriber_phone ".$subscriber_phone, null, 'erase-this.log');
				Mage::log("subscriber_email ".$subscriber_email, null, 'erase-this.log');
				
				#$nowdate = date("Ymd");
				
				
				$utcdate = gmdate("Y-m-d", strtotime(date("Y-m-d")));
				
				
				#$whenentered = Varien_Date::formatDate($nowdate, false);

		$insert_data = array(
			'order_item_id'=>$productkey,
			'order_id'=>$orderId,
			'order_increment_id'=>$orderIncrementId,
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
			
		$insert_data_debug[] = $insert_data;
		
		$model = Mage::getModel('subscriptioninfo/orders')->setData($insert_data);
		try {
			$insertId = $model->save()->getId();
			#Mage::log("Data successfully inserted. Insert ID:  ".$insertId, null, 'finalsave-testing.log');
		} catch (Exception $e){
			Mage::log("exception ".$e->getMessage(), null, 'finalsave-errors.log');
		}

				}
	
	
	$product_id = $sedonarecord['product_id'];
    $product_sku = $sedonarecord['product_sku'];
    $product_price = $sedonarecord['product_price'];
    $product_name = $sedonarecord['product_name'];
    
    $sedonamonitoringinfo = $sedonarecord['monitoring'];
	$monitoring_product_name = $sedonamonitoringinfo['product_name'];
    $monitoring_itemId = $sedonamonitoringinfo['itemId'];
    $monitoring_product_id = $sedonamonitoringinfo['product_id'];
    $monitoring_product_sku = $sedonamonitoringinfo['product_sku'];
    $monitoring_initialbillingamount = $sedonamonitoringinfo['initialbillingamount'];
    $monitoring_recurringbillingamount = $sedonamonitoringinfo['recurringbillingamount'];
    $monitoring_product_price = $sedonamonitoringinfo['product_price'];
    $monitoring_plus_shipping_price = $monitoring_product_price+$shipping_amount;
    
    $sedonamonitoring = $sedonamonitoringinfo['sedonamonitoring'];
    $systemcode = $sedonamonitoringinfo['systemcode'];
    $billeveryxmonths = $sedonamonitoringinfo['billeveryxmonths'];
    $startrecbillinxmonths = $sedonamonitoringinfo['startrecbillinxmonths'];
    
    
	$paymentAmountRecurring = $monitoring_recurringbillingamount;


	if ($CustomerId && $CustomerBillId) {
		
		$CustomerSite_arr = array(
				 'CustomerId' => $CustomerId,
				 'CustomerBillId' => $CustomerBillId,
				 'IsCommercial' => false,
				 'SiteName' => "$subscriber_lastname, $subscriber_firstname",
				 'Address1' => $subscriber_address,
				 'Address2' => $subscriber_address2,
				 'City' => $subscriber_city,
				 'State' => $subscriber_state,
				 'Zip' => $subscriber_zip,
				 'CountryAbbrev' => 'USA',
				 'Phone1' => $subscriber_phone,
				 'Email' => $subscriber_email,
				 'BranchCode' => $BranchCode);
				 
		$this->save_array_data($CustomerSite_arr);
		
		
		if ($doCustomerSite) {
		
		$connection->entity = "CustomerSite";
		$connection->Insert($CustomerSite_arr);
				 
		$showresponse = $connection->GetResponse();
		$error = $connection->GetError($showresponse);
		if ($error) { 
			Mage::log("CustomerSite insertion error: $error", null, 'sedona-errors.log');
			 }		
		else {
			$CustomerSiteId = $showresponse['CustomerSiteId'];
			Mage::log("CustomerSiteId: $CustomerSiteId", null, 'sedona.log');
		}
		
		}
			
		}
		
		
	
	$ContractStartDate = $CurrentDate;
	$WarrantyDate = $CurrentDate;


	$doCustomerSystem = false;
	

	if ($CustomerId && $CustomerBillId && $CustomerSiteId) {
		
		$CustomerSystem_arr = array(
				 'CustomerId' => $CustomerId,
				 'CustomerSiteId' => $CustomerSiteId,
				 'InvoiceDescriptionId' => "1",
				 'SystemCode' => $systemcode,
				 'AlarmAccount' => $CustomerNumber,
				 'ContractStartDate' => $ContractStartDate,
				 'WarrantyDate' => $WarrantyDate);
				 
		$this->save_array_data($CustomerSystem_arr);
		
	if ($doCustomerSystem) {
		
		$connection->entity = "CustomerSystem";
		$connection->Insert($CustomerSystem_arr);
		$showresponse = $connection->GetResponse();
		$error = $connection->GetError($showresponse);
		if ($error) { 
			Mage::log("CustomerSystem insertion error: $error", null, 'sedona-errors.log');
			}
		else {
			$CustomerSystemId = $showresponse['CustomerSystemId'];
			Mage::log("CustomerSystemId: $CustomerSystemId", null, 'sedona.log');
			}
		}

		}
	/*
		sedona_system = Landline Climax
		sedona_systemitemcode = Landline Mon
		sedona_systempartcode = CTC-1041
*/


	$doCustomerRecurring = false;
	
	$sedonamonitoring_expl = explode(":",$sedonamonitoring);
	$sedona_systemitemcode = trim($sedonamonitoring_expl[0]);
	$sedona_systempartcode = trim($sedonamonitoring_expl[1]);
	
	if ($CustomerId && $CustomerBillId && $CustomerSiteId && $CustomerSystemId) {
		
		
		
	
		
		#had itemcode as "N/A" to make work...
			
	if ($today_d > 28) { $today_d = 28; }
	
	#$NextCycleDate = date("Y-m-d",mktime(0,0,0,$today_m+$billeveryxmonths,$today_d,$today_y));
	
	#$FirstDayOfNextCycle = date("Y-m-d",mktime(0,0,0,$today_m+$billeveryxmonths,1,$today_y));
	$FirstDayOfNextCycle = date("Y-m-d",mktime(0,0,0,$today_m+$startrecbillinxmonths,1,$today_y));

	$NextCycleDate = $FirstDayOfNextCycle;
	$CycleStartDate = $CurrentDate;
	$ServiceStartDate = $CurrentDate;
	$ServiceEndDate = $FirstDayOfNextCycle;

	$yearmultiplier_arr[12] = 1;
	$yearmultiplier_arr[3] = 4;
	$yearmultiplier_arr[1] = 12;
	
	$yearmultiplier = $yearmultiplier_arr[$billeveryxmonths];
	
	$YearlyAmount = $paymentAmountRecurring * $yearmultiplier;
	
	$CycleAmount = $paymentAmountRecurring;
	$MonthlyAmount = $YearlyAmount / 12;
	$MonthlyAmount = number_format($MonthlyAmount,2);
	$MonthlyAmount = preg_replace("/[^0-9\.]/i","",$MonthlyAmount);
	
	
	
	#Mage::log("MonthlyAmount $MonthlyAmount", null, 'sedona.log');

	

	
		$BillCycle_arr[12] = "A";
		$BillCycle_arr[3] = "Q";
		$BillCycle_arr[1] = "M";
		#$BillCycle = "M";
		$BillCycle = $BillCycle_arr[$billeveryxmonths];
		
		$BillOnDay = date("d");
		
		if ($BillOnDay > 28) { $BillOnDay = 28; }
		
		$CustomerRecurring_arr = array(
				 'CustomerId' => $CustomerId,
				 'CustomerBillId' => $CustomerBillId,
				 'CustomerSiteId' => $CustomerSiteId,
				 'CustomerSystemId' => $CustomerSystemId,
				 'ItemCode' => $sedona_systemitemcode,
				 'BillCycle' => $BillCycle,
				 'CycleAmount' => $CycleAmount,
				 'MonthlyAmount' => $MonthlyAmount,
				 'CycleStartDate' => $CycleStartDate,
				 'NextCycleDate' => $NextCycleDate,
				 'BillOnDay' => $BillOnDay,
				 'BranchCode' => $BranchCode,
				 'RMRReasonCode' => 'N/A'
				 );
				 
		$this->save_array_data($CustomerRecurring_arr);
		
		if ($doCustomerRecurring) {
		
		$connection->entity = "CustomerRecurring";
		
		$connection->Insert($CustomerRecurring_arr);
				
				
				 
		$showresponse = $connection->GetResponse();
	$error = $connection->GetError($showresponse);
		if ($error) { 
			Mage::log("CustomerRecurring insertion error: $error", null, 'sedona-errors.log');
			}		
			else {
			
		$CustomerRecurringId = $showresponse['CustomerRecurringId'];
		Mage::log("CustomerRecurringId: $CustomerRecurringId", null, 'sedona.log');
		}
	
		}
		}
		
		$AutoProcessDay = "";

	#if ($CustomerId && $CustomerBillId && $CustomerSiteId && $CustomerSystemId && $CustomerRecurringId) {
	if ($CustomerId && $CustomerBillId) {
		
		$AutoProcessDay = $BillOnDay;
		
		
		$CustomerCC_arr = array(
				 'CustomerId' => $CustomerId,
				 'CustomerBillId' => $CustomerBillId,
				 'CardType' => $CardType,
				 'ExpirationMonth' => $ExpirationMonth,
				 'ExpirationYear' => $ExpirationYear2,
				 'AccountName' => $AccountName,
				 'CustomerAddress' => $CustomerAddress,
				 'CustomerZip' => $CustomerZip,
				 'LastFour' => $LastFour,
				 'UsedForAutoProcess' => 1,
				 'AutoProcessDay' => $AutoProcessDay,
				 'ACHDirectToken' => $ACHDirectToken				
				 );
				 
		$this->save_array_data($CustomerCC_arr);
		
		if ($doCustomerCC) {
		
		$connection->entity = "CustomerCC";
		$connection->Insert($CustomerCC_arr);
		$showresponse = $connection->GetResponse();
	$error = $connection->GetError($showresponse);
		if ($error) { 
			Mage::log("CustomerCC insertion error: $error", null, 'sedona-errors.log');

			 }		
			 else {
		$CustomerCCId = $showresponse['CustomerCCId'];
		Mage::log("CustomerCCId: $CustomerCCId", null, 'sedona.log');
		
		
		
		}
		
		}
		
	if (!$CustomerCCId) {
			Mage::log("Missing CustomerCCId", null, 'sedona.log');
			Mage::log("CustomerBillId: $CustomerBillId", null, 'sedona.log');
			Mage::log("CardType: $CardType", null, 'sedona.log');
			Mage::log("ExpirationMonth: $ExpirationMonth", null, 'sedona.log');
			Mage::log("ExpirationYear2: $ExpirationYear2", null, 'sedona.log');
			Mage::log("AccountName: $AccountName", null, 'sedona.log');
			Mage::log("CustomerAddress: $CustomerAddress", null, 'sedona.log');
			Mage::log("CustomerZip: $CustomerZip", null, 'sedona.log');
			Mage::log("LastFour: $LastFour", null, 'sedona.log');
			Mage::log("AutoProcessDay: $AutoProcessDay", null, 'sedona.log');
			Mage::log("ACHDirectToken: $ACHDirectToken", null, 'sedona.log');
			$this->convert_multi_array($showresponse);
		}
		
		}

		$doCustomerInvoice = false;
	

	if ($CustomerId && $CustomerBillId && $CustomerSiteId && $CustomerSystemId && $CustomerRecurringId && $CustomerCCId) {
		
		
		
		#main monitoring here
			unset($InvoiceItemsList);
			
			$InvoiceItemsList[] = array(
				'ItemCode' => $sedona_systemitemcode,
				'PartCode' => $sedona_systempartcode,
				'CustomerSiteId' => $CustomerSiteId,
				'CustomerSystemId' => $CustomerSystemId,
				'CustomerEquipment' => 1,
				'ServiceStartDate' => $ServiceStartDate,
				'ServiceEndDate' => $ServiceEndDate,
				'Quantity' => 1,
				'Amount' => $monitoring_product_price,
				'WarehouseCode' => 'Main'
				);
				
			#shipping
			if ($shipping_amount > 0) {
			$InvoiceItemsList[] = array(
				'ItemCode' => 'Shipping',
				'PartCode' => '',
				'CustomerSiteId' => $CustomerSiteId,
				'CustomerSystemId' => $CustomerSystemId,
				'CustomerEquipment' => 1,
				'ServiceStartDate' => $ServiceStartDate,
				'ServiceEndDate' => $ServiceEndDate,
				'Quantity' => 1,
				'Amount' => $shipping_amount,
				'WarehouseCode' => 'Main'
				);
				}
				
		#loop through children here
		
		
		
		$sedonachildren = $sedonarecord['children'];
		
		foreach ($sedonachildren as $child) {
		
			$product_name = $child['product_name'];
            $product_product_id = $child['product_id'];
            $product_sku = $child['product_sku'];
            $sedonacodes = $child['sedonacodes'];
            $product_price = $child['product_price'];
            
            $sedonacodes .= ",";
            
            $sedonacodesseparate = explode(",",$sedonacodes);
            
            foreach ($sedonacodesseparate as $eachcode) {
	            
	            $eachcode = trim($eachcode);
	            
	            if ($eachcode) {
		            
		    $product_price_total += $product_price;
            
            $sedonacodes_expl = explode(":",$eachcode);
            $sedona_systemitemcode = trim($sedonacodes_expl[0]);
			$sedona_systempartcode = trim($sedonacodes_expl[1]);
            if ($sedona_systemitemcode && $sedona_systempartcode) {
            $InvoiceItemsList[] = array(
				'ItemCode' => $sedona_systemitemcode,
				'PartCode' => $sedona_systempartcode,
				'CustomerSiteId' => $CustomerSiteId,
				'CustomerSystemId' => $CustomerSystemId,
				'CustomerEquipment' => 1,
				'ServiceStartDate' => $ServiceStartDate,
				'ServiceEndDate' => $ServiceEndDate,
				'Quantity' => 1,
				'Amount' => $product_price,
				'WarehouseCode' => 'Main'
				);
            
					}
					}
					}
			}
		
		
		$CustomerInvoice_arr = array(
				 'CustomerId' => $CustomerId,
				 'CustomerSiteId' => $CustomerSiteId,
				 'CustomerBillId' => $CustomerBillId,
				 'InvoiceDate' => $CurrentDate,
				 'CategoryCode' => "Recurring",
				 'Amount' => $monitoring_plus_shipping_price,
				 'TermCode' => "DOR",
				 'BranchCode' => $BranchCode,
				 'InvoiceItems' => $InvoiceItemsList
				 );
				
				
		$this->save_array_data($CustomerInvoice_arr);
		
					if ($doCustomerInvoice) {

		$connection->entity = "CustomerInvoice";
		$connection->Insert($CustomerInvoice_arr);
		$showresponse = $connection->GetResponse();
		$error = $connection->GetError($showresponse);
			if ($error) { 
				Mage::log("CustomerInvoice insertion error: $error", null, 'sedona-errors.log');
				$this->convert_multi_array($InvoiceItemsList);
			 	}		
			else {
			 	$InvoiceId = $showresponse['InvoiceId'];
				Mage::log("InvoiceId: $InvoiceId", null, 'sedona.log');
				}
		
		}
		}	
	
	#if ($CustomerId && $CustomerBillId && $CustomerSiteId && $CustomerSystemId && $CustomerRecurringId && $CustomerCCId && 	$InvoiceId) {
	if ($CustomerId && $CustomerBillId && $CustomerSiteId) {
		
		$CustomerInvoicePaymentList[] = array(
				 'InvoiceNumber' => $InvoiceNumber,
				 'Amount' => $monitoring_plus_shipping_price
				 );
		
		$CustomerPayment_arr = array(
				 'CustomerNumber' => $CustomerNumber,
				 'PaymentMethodCode' => $ForteCardType,
				 'Amount' => $monitoring_plus_shipping_price,
				 'BranchCode' => $BranchCode,
				 'CustomerInvoicePayment' => $CustomerInvoicePaymentList
				 );
				 
		$this->save_array_data($CustomerPayment_arr);
		
		if ($doCustomerPayment) {
		
		$connection->entity = "CustomerPayment";
		$connection->Insert($CustomerPayment_arr);
		$showresponse = $connection->GetResponse();
	$error = $connection->GetError($showresponse);
		if ($error) { 
		Mage::log("CustomerPayment insertion error: $error", null, 'sedona-errors.log');

			 }	
			 else {	
		$DepositCheckId = $showresponse['DepositCheckId'];
		Mage::log("DepositCheckId: $DepositCheckId", null, 'sedona.log');
		}
	
		}
		}
		

		
	#if ($CustomerId && $CustomerBillId && $CustomerSiteId && $CustomerSystemId && $CustomerRecurringId && $CustomerCCId && $InvoiceId && $DepositCheckId) {
	if ($CustomerId && $CustomerBillId && $CustomerSiteId) {
		
		$CustomerContact_arr = array(
				 'CustomerId' => $CustomerId,
				 'CustomerBillId' => $CustomerBillId,
				 'CustomerSiteId' => $CustomerSiteId,
				 'ContactName' => "$shipping_firstname, $shipping_lastname",
				 'Notes' => "$shipping_address $shipping_address2, $shipping_city, $shipping_state_abbrev, $shipping_zip",
				 'Phone' => $shipping_phone,
				 'Email' => $shipping_email
				 );
				 
		$this->save_array_data($CustomerContact_arr);
		
		if ($doCustomerContact) {
				 
		$connection->entity = "CustomerContact";
		$connection->Insert($CustomerContact_arr);
		$showresponse = $connection->GetResponse();
	$error = $connection->GetError($showresponse);
		if ($error) { 
			
					Mage::log("CustomerContact insertion error: $error", null, 'sedona-errors.log');

 }		else  {
		$CustomerContactId = $showresponse['CustomerContactId'];
		Mage::log("CustomerContactId: $CustomerContactId", null, 'sedona.log');
		}
		
		}
		
		}
		
		

if ($error) {
	 $returnerror = "There has been an error in processing your order. 
A Customer Care Representative will be happy to help complete your order. 
Please call us toll free at 1(800)994-2095. The Customer Care Center is open 8am-5pm CST M-F";
		}
		}
	
	
	}

}
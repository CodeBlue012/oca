<?php

/*
		changing live to test and vice versa requires three file changes:
		connect-forte-classes.php
		payment-sedona-classes.php
		Paymentmethod.php	
		*/
		
			
	class AchdirectCMIException extends Exception {}

class Authentication {    
    public $APILoginID;
    public $SecureTransactionKey;
}

class Client {
	function Client($mid, $fn, $ln) {
		$this->MerchantID = $mid;
		$this->FirstName = $fn;
		$this->LastName = $ln;
		$this->ClientID = 0;
		}
	}
	 	
class PaymentMethod {
	function PaymentMethod($mid, $clientid, $payment_arr) {
		$this->MerchantID = $mid;
		$this->ClientID = $clientid;
		$this->PaymentMethodID = 0;
		$this->AcctHolderName = $payment_arr['AcctHolderName'];
		$this->CcCardNumber = $payment_arr['CcCardNumber'];
		$this->CcExpirationDate = $payment_arr['CcExpirationDate'];
		$this->CcCardType = $payment_arr['CcCardType'];
		}
	}

class Achdirect_cmi {
    
	
	private $MerchantID = 172093;
	
	#test change in two spots - spot 1/2
    private $ApiLoginID = "fkavCo3AK";
    private $SecureTransactionKey = "hBEPDDK2lf7yby";
    
    #live
    #private $ApiLoginID = "Th2OnRXr3h";
	#private $SecureTransactionKey = "V8WRxkyAjCwFnx";
    
    
	public $FirstName;
	public $LastName;
	public $payment_arr;
	#public $AcctHolderName;
	#public $CcCardNumber;
	#public $CcExpirationDate;
	#public $CcCardType;
	public $CcProcurementCard = false;
	public $IsDefault = true;
	public $SuppressAccountUpdater = true;

    public function __construct()
    {
        if (empty($this->MerchantID) || empty($this->ApiLoginID) || empty($this->SecureTransactionKey) )
        {
            throw new AchdirectCMIException("You have not configured your Payments Gateway login credentials.");
        }
        
        //use the BasicHttpBinding endpoint located at CMIService.svc/Basic

		#test - change in two spots - spot 2/2
		$this->url = "https://sandbox.paymentsgateway.net/WS/Client.wsdl";
        $this->location = "https://sandbox.paymentsgateway.net/ws/Client.svc/basic";
        
        #live    
        #$this->url = "https://ws.paymentsgateway.net/Service/v1/Client.wsdl";
        #$this->location = "https://ws.paymentsgateway.net/Service/v1/Client.svc/basic";
    }
    
    public function hmac ($key, $data)
	{
		// RFC 2104 HMAC implementation for php.
		// Creates an md5 HMAC.
		// Eliminates the need to install mhash to compute a HMAC
		// Hacked by Lance Rushing

		$b = 64; // byte length for md5
		if (strlen($key) > $b) {
		$key = pack("H*",md5($key));
		}
		$key = str_pad($key, $b, chr(0x00));
		$ipad = str_pad('', $b, chr(0x36));
		$opad = str_pad('', $b, chr(0x5c));
		$k_ipad = $key ^ $ipad ;
		$k_opad = $key ^ $opad;

		return md5($k_opad . pack("H*",md5($k_ipad . $data)));
	}
    
	public function createClient()
    {

		$time = time();
		$multiplied = $time * 10000000; //adjust to microseconds
		$addedtime = $multiplied + 621355968000000000; //adjust date from epoch to .net. not exact but close.
		$time = time() + 62135596800;
		$addedtime = $time . '0000000';
        $Authentication = new Authentication();
        $Authentication->APILoginID = $this->ApiLoginID;
        $Authentication->SecureTransactionKey = $this->SecureTransactionKey;
        
        $Authentication->TSHash = $this->hmac($this->SecureTransactionKey,$this->ApiLoginID . "|" . $addedtime);
        $Authentication->UTCTime = $addedtime;
        
        try
        {
     	    $client = new SoapClient($this->url, array("location" => $this->location));     	    
			$Newclient = new Client($this->MerchantID,$this->FirstName,$this->LastName);
            $params = array("ticket" => $Authentication, "client" => $Newclient);
            $response = $client->createClient($params);
            #print "<pre>";
            #var_dump($response);
            #print "</pre>";
            #print $response->createClientResult;
            $this->soapClientToken = $response->createClientResult;
            
            
            
        }
        catch (Exception $e)
        {            
            $forteerror = $e->getMessage();
            #Mage::throwException($forteerror);
            #Mage::log($forteerror, null, $this->getCode().'-testing.log');
        }
    }

	public function createPaymentMethod()
    {

		$this->soapPaymentToken = "";
        $this->showsoaperror = "";

		$time = time();
		$multiplied = $time * 10000000; //adjust to microseconds
		$addedtime = $multiplied + 621355968000000000; //adjust date from epoch to .net. not exact but close.
		$time = time() + 62135596800;
		$addedtime = $time . '0000000';
        $Authentication = new Authentication();
        $Authentication->APILoginID = $this->ApiLoginID;
        $Authentication->SecureTransactionKey = $this->SecureTransactionKey;
        
        $Authentication->TSHash = $this->hmac($this->SecureTransactionKey,$this->ApiLoginID . "|" . $addedtime);
        $Authentication->UTCTime = $addedtime;
        
        try
        {
     	    $paymentmethod = new SoapClient($this->url, array("location" => $this->location));     
			$Newpaymentmethod = new PaymentMethod($this->MerchantID,$this->soapClientToken,$this->payment_arr);
            $params = array("ticket" => $Authentication, "payment" => $Newpaymentmethod);
            $response = $paymentmethod->createPaymentMethod($params);
            #print "<pre>";
            #var_dump($response);
            #print "</pre>";
            #print $response->createPaymentMethodResult;
            
            $out = print_r($response,true);
			Mage::log($out, null, 'forte-errors.log');

            $this->soapPaymentToken = $response->createPaymentMethodResult;
            
            
            
        }
        catch (Exception $e)
        {            
             $forteerror = $e->getMessage();
             $this->showsoaperror = $forteerror;
             
			  Mage::log($forteerror, null, 'forte-errors.log');
            
             #Mage::throwException($forteerror);
			 #Mage::log($forteerror, null, $this->getCode().'-testing.log');
        }
    }


}

?>
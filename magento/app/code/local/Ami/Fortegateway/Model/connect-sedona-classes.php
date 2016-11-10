<?php

define('QUERY_OK',200);
define('INSERT_OK',201);
define('CHANGE_OK',204);
define('FATAL_ERROR','FATAL');

// Connection class with sedona OData web service
class rest_class
{
	// address of the service, only the the base adress
	public $url;
	// user to be used to connect to the service
	public $user;
	// password to be used to connect to the service
	public $password;
	// flag to be able to print debug messages from the service
	public $debug = false;	
	// entity to be used in the operation, in this way you can execute several operations over the same entity
	public $entity = null;
	// condition required for some operations
	private $condition = '';
	// method required to execute
	private $method = 'GET';
	// data to be passed to the service, especifically for inserts or updates
	private $data;
	// required connection header
	private $headers = array('Accept: application/json','Content-Type: application/json');
	// will hold the response of the last operation executed
	private $response = '';
	// will store the code of the last operation executed
	private $code;
	// will keep the error message occurred in the last operation executed
	private $error = '';
	
	// Main class rest_class constructor, here are passed important values 
	// that not need to be set every time the objects of this class is used.
	function __construct($url, $user, $password) 
	{
		$this->url = $url;
		$this->user = $user;
		$this->password = $password;
	}
	
	// Use this to query database condition is the normal odata condition options
	// return True if operation is successfull
	// if opetation fails (false) some error can be retrieved using GetError function
	public function Query($condition)
	{
		$this->error = '';
		$this->method = "GET";
		$this->condition = str_replace(' ','%20', $condition);
		return $this->Execute() == QUERY_OK;
	}
	
	// Insert the given data into the database, system generates unique guid for the record
	// return True if operation is successfull
	// if opetation fails (false) some error can be retrieved using GetError function
	public function Insert($data)
	{
		$this->error = '';
		$this->method = "POST";
		$this->data = $data;
		return $this->Execute() == INSERT_OK;
	}
	
	// updates the record that correspond to the given $id that should exists in database or it will return an error
	// data needs to contain the fields with values to be updated
	// return True if operation is successfull
	// if opetation fails (false) some error can be retrieved using GetError function
	// IMPORTANT: odata udpate operation only works with ID, don't try to indicate a condition to update several records
	public function Update($id, $data)
	{
		$this->error = '';
		$result = false;
		$id = strtoupper($id);
		if($id=='')
		{
			$this->error = 'Sedona ODATA ERROR: required \$id for method Update (id is empty)';
		}
		else if($data==null || $data=='')
		{
			$this->error = 'Sedona ODATA ERROR: no \$data provided for method Update (data to insert is null or empty)';
		}
		else
		{
			$this->method = "MERGE";
			$this->data = $data;
			$this->condition = "(guid'$id')";
			$result = $this->Execute() == CHANGE_OK;
		}
		return $result;
	}
	
	// deletes the record that correspond to the given $id that should exists in database or it will return an error
	// IMPORTANT: if the record to deleted exist but it contains relationships or dependencies to other entities will fail;
	// For this case is required to delete all the relationships first
	// return True if operation is successfull
	// if opetation fails (false) some error can be retrieved using GetError function
	public function Delete($id)
	{
		$this->error = '';
		$result = false;
		if($id=='')
		{
			$this->error = 'Sedona ODATA ERROR: required \$id for method Delete (id is empty)';
		}
		else
		{
			$this->method = "DELETE";
			$this->condition = "(guid'$id')";
			$result = $this->Execute();
		}
		return $result;
	}
	
	// Return the latest response of the latest OData operation
	public function GetResponse()
	{
		return json_decode($this->response,true);
	}
	
	// Return the latest response but as it was delivered by the service
	public function GetCrudeResponse()
	{
		return $this->response;
	}
	
	// Return the code generated from the latest OData operation
	public function GetCode()
	{
		return $this->code;
	}
	
	// Return the record id from the latest OData operation that must be a query or insert operation
	// if the query generates several records, this function will return the guid of the first record in the list
	public function GetGuid()
	{
		$result = $this->response;
		$pattern = $this->entity . "(guid'";
		$index = strpos($result,$pattern);
		if($index === false)
		{
			$this->error = "guid pattern not found";
			return '';
		}
		else
		{
			return substr($result, $index+strlen($pattern), 36);
		}
	}
	
	// Returns the last error message occurred in the use of this 
	public function GetError($response)
	{
		$message = "";
		$response_string = json_encode($response);			
		if (preg_match("/ModelState/i",$response_string) && preg_match("/validationError/i",$response_string)) {
			$message = $response['ModelState']['validationError'][0];
			$out = print_r($response,true);
			Mage::log($out, null, 'sedona-errors.log');
			}
		elseif (preg_match("/error/i",$response_string)) {
			$message = "Unknown Error Encountered";
			$out = print_r($response,true);
			Mage::log($out, null, 'sedona-errors.log');
			}
		return $message;
	}
		
	// print messages only if the debug flag is on
	private function Debug($text)
	{
		if($this->debug)
		{
			#print $text;
				Mage::log("Sedona Debug: $text", null, 'sedona.log');

		}
	}
			
	// executes the final call to OData
	private function Execute()
	{
		try
		{
			if($this->entity!=null)
			{
				$this->Debug("Executing method $this->method over $this->entity");
				# checking for the proper entity name pattern
				#if (substr($this->entity, -3) !== 'Set')
				#{
				#	print("WARNING: entity $this->entity doesn't ends with 'Set'");
				#}
				$this->Debug("Connecting to sedona...");
				$handle = curl_init();
				$url = $this->url . $this->entity;
				if($this->condition!='')
				{
					$url .= $this->condition;
				}
				curl_setopt($handle, CURLOPT_URL, $url);
				curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				#curl_setopt($handle, CURLOPT_USERPWD, $this->companyId .':'. $this->user .':'. $this->password );
				curl_setopt($handle, CURLOPT_USERPWD, $this->user .':'. $this->password );
				curl_setopt($handle, CURLOPT_HTTPHEADER, $this->headers);
				curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
				$data = json_encode($this->data);
				$this->Debug("Executing with URL=$url");
				switch($this->method)
				{
					case 'GET':
						// query no need to sent extra data, just the query that was already set on url
						break;
					case 'POST': // Insert
						$this->Debug("Data sent is: $data");
						curl_setopt($handle, CURLOPT_POST, true);
						curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
						break;
					case 'PUT': // Replace (WARNING try not to use this one, empty field provided could replace current information in database) 
						$this->Debug("Data sent is: $data");
						curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
						curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
						break;
					case 'MERGE': 
						$this->Debug("Data sent is: $data");
						curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'MERGE');
						curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
						break;
					case 'DELETE':
						curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
						break;
				}
				$this->response = curl_exec($handle);
				
				#print "xxx ".$this->response;
				
				$this->code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
				curl_close($handle); 
				return $this->code;
			}
			else
			{
				$this->error = "entity field not provided, need to indicate the name of the entity to be used.";
				return FATAL_ERROR;
			}
		}
		catch(Exception $exception)
		{
			$this->error = $exception->getMessage();
			return FATAL_ERROR;
		}
	}
}

?>
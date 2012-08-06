<?php
    // Include the PHP TwilioRest library 
    require "twilio.php";
    
    // Twilio REST API version 
    $ApiVersion = "2010-04-01";
    
    // Set our AccountSid and AuthToken 
    $AccountSid = "ACbe5ef8d477fb3ba31549f3012e9ba769";
    $AuthToken = "e877310d768bcbde705235d4b0298caa";
    
    // Outgoing Caller ID you have previously validated with Twilio 
    //$CallerID = '424-245-9689';
    $CallerID = '415-599-2671';
    // Instantiate a new Twilio Rest Client 
    $client = new TwilioRestClient($AccountSid, $AuthToken);
    
    // ========================================================================
    // 1. Initiate a new outbound call to 415-555-1212
    //    uses a HTTP POST
    $data = array(
    	"From" => $CallerID, 	      // Outgoing Caller ID
    	"To" => "424-245-9689",	  // The phone number you wish to dial
    	"Body" => "This is a test message from Xebura! Reply STOP to unsubscribe"
    );
    
    $response = $client->request("/$ApiVersion/Accounts/$AccountSid/SMS/Messages", 
       "POST", $data); 
    
	print_r($response);
    // check response for success or error
    if($response->IsError)
    	echo "Error sending text: {$response->ErrorMessage}\n";
    else
    	echo "Sent text: {$response->ResponseXml->Call->Sid}\n";
    

    
    ?>
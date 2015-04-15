<?php
/*
 * StrikeIron North American Address Verification 6.0.0
 * http://www.strikeiron.com/Catalog/ProductDetail.aspx?pv=6.0.0&pn=North+American+Address+Verification
 *
 * Required:
 *
 *  1. PHP 5 with soap extensions
 *  2. A registered StrikeIron account with a userid/password.
 *		To register please visit: http://www.strikeiron.com/Register.aspx
 * 		Then set the $USER_ID and $PASSWORD below
 *
 * To run place this script, copy it to a directory on your web server and access with a browser.
 *
 *
 * For additional support:
 *  email: support@strikeiron.com
 *  phone: +1 919-467-4545 opt. 3
 *
 *
 */

//StrikeIron credentials: if using a license key, set the value in the User ID field and leave the password field blank
//$USER_ID = 'naveenkhanchandani@gmail.com';
//$PASSWORD = 'snusRZ';
$USER_ID = 'D39FEF896C6F4B5DA6E8';
$PASSWORD = '';
//Stores WSDL URL
$WSDL = 'http://ws.strikeiron.com/USAddressVerification4_0?WSDL'; //'http://ws.strikeiron.com/NAAddressVerification6?WSDL';

//instantiate SOAP client
$client = new SoapClient($WSDL, array('trace' => 1, 'exceptions' => 1));

//create SOAP header object
$registered_user = array("RegisteredUser" => array("UserID" => $USER_ID, "Password" => $PASSWORD));
$header = new SoapHeader("http://ws.strikeiron.com", "LicenseInfo", $registered_user);

//attach header to client
$client->__setSoapHeaders($header);

try
{
  //Call NA Address Verification with US Address
  //VerifyUSAddress();

  //Call NA Address Vericiation with Canada Address
  VerifyCanadaAddress();
}
catch (Exception $ex)
{
  echo $ex->getMessage() . '<br>';
}

function VerifyUSAddress()
{
  global $client;

  //set up input parameters with US Address
  $addressLine1 = '15501 Weston Pkwy';
  $addressLine2 = 'Suite 150';
  $cityStateZIP = 'Cary NC 27513';
  $country = 'US';
  $casing = 'UPPER';
  $addressLine1 = '1230 san tomas aquino rd #114';
  $addressLine2 = '';
  $cityStateZIP = 'San Jose CA 95117';
  $country = 'US';
  $casing = 'PROPER';

  //set up parameter array
  /*$params = array("AddressLine1" => $addressLine1,
          "AddressLine2" => $addressLine2,
          "CityStateOrProvinceZIPOrPostalCode" => $cityStateZIP,
          "Country" => $country,
          "Casing" => $casing);*/
   $params = array
			(
				'addressLine1'   => $addressLine1,
				'addressLine2'   => $addressLine2,
				'city_state_zip' => "San Jose CA 95117",
				'firm'           => '',
				'urbanization'   => '',
				'casing'         => 'Proper'
			);


  //call the web service operation
  //$result = $client->__soapCall("NorthAmericanAddressVerification", array($params), null, null, $output_header);
  $result = $client->__soapCall("VerifyAddressUSA", array($params), null, null, $output_header);

  echo '<pre>';
  print_r($result);
  echo '</pre>';
  exit;
  //show service results
  //note that these are not all the fields contained within the service
  ouput_US_Result($result->NorthAmericanAddressVerificationResult->ServiceResult->USAddress);

  //show status info
  output_status_info($result->NorthAmericanAddressVerificationResult->ServiceStatus);
}

function VerifyCanadaAddress()
{
  global $client;
  
  //set up input parameters with Canada Address
  $addressLine1 = '2 Robert Speck Pkwy';
  $addressLine2 = 'Suite 750';
  $cityProvincePostal = 'Mississauga ON L4Z 1H8';
  $country = 'CA';
  $casing = 'UPPER';
  
  //set up parameter array
  $params = array("AddressLine1" => $addressLine1,
            "AddressLine2" => $addressLine2,
            "CityStateOrProvinceZIPOrPostalCode" => $cityProvincePostal,
            "Country" => $country,
            "Casing" => $casing);
  
  //call the web service operation
  $result = $client->__soapCall("NorthAmericanAddressVerification", array($params), null, null, $output_header);
  //show service results
  //note that these are not all the fields contained within the service
  output_Canada_Result($result->NorthAmericanAddressVerificationResult->ServiceResult->CanadaAddress);

  //show status info
  output_status_info($result->NorthAmericanAddressVerificationResult->ServiceStatus);

  //show subscripion inffo
  output_subscription_info($output_header['SubscriptionInfo']);
}

function ouput_US_Result($usAddress)
{
  echo '<h1><span style="FONT-FAMILY: Arial; FONT-WIGHT: bold">US Address Result:</span></h1><br>';
  echo '<table border="1" style="FONT-FAMILY: Arial">';
       echo '<tr><td>Address Line 1:</td><td>' . $usAddress->AddressLine1 . '</td></tr>';
       echo '<tr><td>Address Line 2:</td><td>' . $usAddress->AddressLine2 . '</td></tr>';
       echo '<tr><td>City:</td><td>' . $usAddress->City . '</td></tr>';
       echo '<tr><td>State:</td><td>' . $usAddress->State . '</td></tr>';
       echo '<tr><td>ZIP Code:</td><td>' . $usAddress->ZIPPlus4 . '</td></tr>';
       echo '<tr><td>DPV:</td><td>' . $usAddress->DPV . '</td></tr>';
  echo '</table><br><br>';
}

function output_Canada_Result($caAddress)
{
  echo '<h1><span style="FONT-FAMILY: Arial; FONT-WEIGHT: bold">Canada Address Result:</span></h1><br>';
  echo '<table border="1" style="FONT-FAMILY: Arial">';
       echo '<tr><td>Address Line 1:</td><td>' . $caAddress->AddressLine1 . '</td></tr>';
       echo '<tr><td>Address Line 2:</td><td>' . $caAddress->AddressLine2 . '</td></tr>';
       echo '<tr><td>City:</td><td>' . $caAddress->City . '</td></tr>';
       echo '<tr><td>Province:</td><td>' . $caAddress->Province . '</td></tr>';
       echo '<tr><td>Postal Code:</td><td>' . $caAddress->PostalCode . '</td></tr>';
  echo '</table><br><br>';
}

function output_status_info( $status_info )
{
	echo '<table border="1">';
		echo '<tr><td>Status Description</td><td>' . $status_info->StatusDescription . '</td></tr>';
		echo '<tr><td>Status Nbr</td><td>' . $status_info->StatusNbr . '</td></tr>';
	echo '</table><br><br>';
}

function output_subscription_info( $subscription_info )
{
	echo '<table border="1">';
		echo '<tr><td>License Status</td><td>' . $subscription_info->LicenseStatus . '</td></tr>';
		echo '<tr><td>License Action</td><td>' . $subscription_info->LicenseAction . '</td></tr>';
		echo '<tr><td>Remaining Hits</td><td>' . $subscription_info->RemainingHits . '</td></tr>';
	echo '</table>';
}

?>
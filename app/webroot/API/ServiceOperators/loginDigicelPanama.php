<?php
/**
 * Login to Digicel Panama
 * Club Prepago API
 *
 * @copyright     Club Prepago Celular(tm) Project
 * @link          http://www.clubprepago.com
 * @package       API.ServiceOperators.loginDigicelPanama
 * @since         Club Prepago Celular(tm) v 1.1
 */

 //This script is executed by crontab
 //0 0 * * * /usr/local/bin/php /var/www/app/webroot/API/ServiceOperators/loginDigicelPanama.php

 include "../../APIConfig/Dbconn.php";
 //include "../../APIConfig/config.php";
 //include "../../APIConfig/ServerStatusCodes.php";

 // Initializing logger
 //$logger = new Katzgrau\KLogger\Logger(LOG_DIR);
 //$logger->notice("=== Login daily digicelPanama ==========================================");

 // Get credentials settings for the mobile operator
 $db = new Dbconn();
 $query = "SELECT * FROM operator_credentials WHERE operator_id = 2";
 $resquery = $db->fireQuery($query);
 $arropcr = $db->fetchAssoc($resquery);

 // Session Parameters
 $username = $arropcr['username'];
 $password = $arropcr['password'];
 $url = $arropcr['ip_address'];
 $loginKey = $arropcr['token'];

 // Options
 $options = array("connection_timeout" => 15);

 // To do login
 $Arraylogin = login($username, $password, $url, $options);
 print_r($Arraylogin);
 echo(json_encode($Arraylogin));

 // Parsing response
 $respCodeLogin = $Arraylogin['return']['responseCode'];

 if ($respCodeLogin == '0') {

   $newLoginKey = $Arraylogin['return']['loginKey'];
   $update = $db->fireQuery("UPDATE operator_credentials
                              SET token = ".$newLoginKey."
                              WHERE operator_id = 2"
                  );
   $rowCount = $this->fireQuery($update);

   if ($rowCount) {

      //$logger->notice("Response of Operator: " .$this->generateJSONError('533'));
      echo ("Update Successful");

   }

 } elseif ($respCodeLogin == '405') {

   $Arraylogout = logout($loginKey, $url, $options);

   // Parsing response
   $respCodeLogout = $Arraylogout['return']['responseCode'];

   if ($respCodeLogout == 0) {

     $Arraylogin = login($username, $password, $url, $options);

   } else {

     echo "Something went wrong";

   }

 } elseif ($respCodeLogin == '404') {

   echo ("Authentication error - The system rejected your username/password.");

 } else {

   echo "Something went wrong";

 }

 function login($username, $password, $url, $options){

  $responseArray = array();

  // Create parameters
  $params = array('arg0' => $username, 'arg1' => $password);
  //$logger->notice("Parameters for request: ".json_encode($responseArray));

  // Establish connection to WSDL
  $client = new SoapClient($url, $options);

  try {

    // Call Login method and store results in response array
    $responseObject = $client->login($params);
    $responseArray = json_decode(json_encode($responseObject), TRUE);
    //$logger->notice("Response of Operator: ".$responseArray);

  } catch (Exception $e) {

    echo $e;

  }

  return $responseArray;

 }

 function logout($loginKey, $url, $options){

   $responseArray = array();

   // Create parameters
   $params = array('arg0' => $loginKey);
   //$logger->notice("Parameters for request: ".json_encode($responseArray));

   // Establish connection to WSDL
   $client = new SoapClient($url, $options);

   try {

     // Call Login method and store results in response array
     $responseObject = $client->login($params);
     $responseArray = json_decode(json_encode($responseObject), TRUE);
     //$logger->notice("Response of Operator: ".$responseArray);

   } catch (Exception $e) {

     echo $e;

   }

   return $responseArray;

 }
?>

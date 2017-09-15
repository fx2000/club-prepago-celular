<?php
/**
 * Send a new Recharge to Cable and Wireless Panama
 * Club Prepago API
 *
 * @copyright     Club Prepago Celular(tm) Project
 * @link          http://www.clubprepago.com
 * @package       API.ServiceOperators.digicelPanama
 * @since         Club Prepago Celular(tm) v 1.1
 */

  class digicelPanama extends Dbconn {
    function doRecharge($data) {

      // Initializing logger
  		$logger = new Katzgrau\KLogger\Logger(LOG_DIR);

  		$logger->notice("=== Recharge digicelPanama ==========================================");
  		$logger->notice("Data from Request:", $data);

      // Get trxengine settings for the mobile operator
      $selproduct =
        "SELECT *
          FROM operator_credentials
          WHERE operator_id = ". $data['Operator'];
      $resproduct = $this->fireQuery($selproduct);
      $arrproduct = $this->fetchAssoc($resproduct);

      // Assign appropriate values
      $url = $arrproduct['ip_address'];
      $loginKey = $arrproduct['token'];

      $logger->notice("Operator Credentials:". $url." ".$loginKey);

      $options = array('cache_wsdl'=>WSDL_CACHE_NONE,
                       'connection_timeout'=>15,
                       'exceptions'=>true);

      // Crete parameters
      $params = array(
        'arg0'  => $loginKey,
        'arg1'  => $data['MerchantTxnId'],
        'arg2'  => date('Y-m-d') . 'T' . date('H:i:s'),
        'arg3'  => '507' . $data['Phone_Number'],
        'arg4'  => $data['Amount'] . '.00',
        'arg5'  => 'PAB',
        'arg6'  => ''
       );
       $logger->notice("Parameters for request:". json_encode($params));

       // Establish connection to WSDL
       $client = new SoapClient($url, $options);

       // Call rechargeMSISDN method and store results in response array
       $responseObject = $client->rechargeMSISDN($params);
       $responseArray = json_decode(json_encode($responseObject), TRUE);
       $logger->notice("Response of Operator:". json_encode($responseObject));

       // Parsing response
       $responseCode = $responseArray['return']['responseCode'];
       $transactionID = $responseArray['return']['transactionID'];
       $customerName = $responseArray['return']['customerName'];
       $transactionTimestamp = $responseArray['return']['transactionTimestamp'];
       $newAccountBalance = $responseArray['return']['newAccountBalance'];
       $rechargeExpiryDate = $responseArray['return']['rechargeExpiryDate'];

       // Display Results
       if ($responseCode == '0') {

         $result = "0:00:".$transactionID.":".$newAccountBalance.":".$rechargeExpiryDate.":0";

       } elseif ($responseCode == '102'){

         $result = "0:2";

       } elseif ($responseCode == '108'){

         $result = "0:9";

       } elseif ($responseCode == '125'){

         $result = "0:7";

       } elseif ($responseCode == '150'){

         $result = "0:6";

       } else {

         $result = "0:99";

       }

       return $result;

    }
  }

?>

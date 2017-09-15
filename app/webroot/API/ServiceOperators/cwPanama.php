<?php
/**
 * Send a new Recharge to Cable and Wireless Panama
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.ServiceOperators.selectorOperator
 * @since         Club Prepago Celular(tm) v 1.0.0
 */

  class cwPanama extends Dbconn {
    function doRecharge($data) {

      // Initializing logger
  		$logger = new Katzgrau\KLogger\Logger(LOG_DIR);

  		// Logging Forgot Password
  		$logger->notice("=== Recharge cwPanama ==========================================");
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
      $port = $arrproduct['port'];
      $merchantId = $arrproduct['username'];
      $merchantPin = $arrproduct['password'];
      $productId = $arrproduct['product_id'];

      $logger->notice("Operator Credentials:". $url." ".$port." ".$merchantId." ".
                                                  $merchantPin." ".$productId);

      $header[] = "Host:" . $url . ":" . $port;
      $header[] = "Content-type: text/xml";
      $NewXml =
        '<methodCall>
        <methodName>roms.esinglextrapr</methodName>
        <params>
        <param><value>' . $merchantId .'</value></param>
        <param><value>' . $merchantPin .'</value></param>
        <param><value>' . $data['Phone_Number'] .'</value></param>
        <param><value>' . number_format((float)$data['Amount'], 2, '.', '') .'</value></param>
        <param><value>' . $data['MerchantTxnId'] .'</value></param>
        <param><value>' . $data['PlatformId'] . '</value></param>
        <param><value>' . $data['UserId'] . '</value></param>
        <param><value>' . date('YmdHis') . '</value></param>
        <param><value>' . $productId . '</value></param>
        </params>
        </methodCall>';
      $logger->notice("Request to Operator:". $NewXml);
      $ch = curl_init();

      // Check Mobile Operator and set target server
      curl_setopt($ch, CURLOPT_URL, $url . ":" . $port);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );

      // Add headers
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

      // Set POST
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

      // Add XML content
      curl_setopt($ch, CURLOPT_POSTFIELDS, $NewXml);

      // Send transaction to trxengine
      $result = curl_exec($ch);

      // Close connection
      curl_close($ch);

      // Read and return results
      $logger->notice("Response of Opetator:". $result);
      $result1 = simplexml_load_string($result);
      $result2 = $result1->params->param->value;
      $result3 = $result2->string;
      return $result3;
    }
  }
?>

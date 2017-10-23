<?php
/**
 * Send a new Recharge to Movistar Venezuela throwgh MovilWay Gateway
 * Club Prepago API
 *
 * @copyright     Club Prepago Celular(tm)
 * @link          http://www.clubprepago.com
 * @package       API.ServiceOperators.movistarVenezuela.php
 * @since         Club Prepago Celular(tm) v 1.1
 */

  class movistarVenezuela extends Dbconn {

    function doRecharge($data){

      // Initializing logger
  		$logger = new Katzgrau\KLogger\Logger(LOG_DIR);

  		// Logging Forgot Password
  		$logger->notice("=== Recharge movistarVenezuela ==========================================");
  		$logger->notice("Data from Request:", $data);

      $selproduct =
        "SELECT *
          FROM operator_credentials
          WHERE operator_id = ". $data['Operator'];
      $resproduct = $this->fireQuery($selproduct);
      $arrproduct = $this->fetchAssoc($resproduct);

      // Assign appropriate values
      $url = $arrproduct['ip_address'];
      $user = $arrproduct['username'];
      $pass = $arrproduct['password'];
      $amount = round($data['Amount']);
      $phone = $data['Phone_Number'];

      $options = array('cache_wsdl'=>WSDL_CACHE_NONE,
                       'connection_timeout'=>15,
                       'exceptions'=>true);

      try {

        $params = array(
                        'AuthenticationData'=>array(
                                              'Username'=>$user,
                                              'Password'=>$pass
                                          ),
                        'DeviceType'=>3,
                        'Platform'=>1,
                        'Amount'=>$amount,
                        'ExternalTransactionReference'=>$data['MerchantTxnId'],
                        'MNO'=>'1', // Only for tests
                        'Recipient'=>$phone,
                        'WalletType'=>'Stock',
                        'TerminalID'=>'34963'
                  );
        $logger->notice("Request to Operator:". json_encode($params));

        $client = new SoapClient($url);
        $responseObject = $client->TopUp($params);
        $logger->notice("Response of Operator:". json_encode($responseObject));

        $responseArray = json_decode(json_encode($responseObject),TRUE);

        $code = $responseArray['ResponseCode'];
        $desc = $responseArray['ResponseMessage'];
        $codRec = $responseArray['TransactionID'];
        $trxCode = $responseArray['ExternalTransactionReference'];
        $bal = $responseArray['StockBalance'];
        $balW = $responseArray['WalletBalance'];
        $balP = $responseArray['PointBalance'];

        if ($code == '0') {

          $result = "0:00:".$codRec.":".$bal.":00:0";

        } else {

          $result = "0:99";

        }

      } catch (Exception $e) {

         $result = "0:99";
         $logger->error("Exception from Operator:". json_encode($e));

      }

      return $result;

    }
  }
?>

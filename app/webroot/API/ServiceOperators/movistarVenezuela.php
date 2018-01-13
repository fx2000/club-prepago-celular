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

      ini_set('default_socket_timeout',15);

      $options = array('connection_timeout'=>15,'exceptions'=>true);

      $result = '';

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

        $logger->error("Exception from Operator:". json_encode($e));

        if (strpos($e->getMessage(), 'Error Fetching http headers') !== false) {

          $logger->error($e->getMessage(). ' Timeout from Topup');

          sleep(2);
          $i = 0;

          do {

              $getTrax = $this->getTransaction($data['Operator'], $data['MerchantTxnId']);
              sleep(5);
              $i++;

          } while ($getTrax['code'] != 0 && $i < 3);

          if ($getTrax['trxCode'] === 0) {

            $result = "0:00:".$getTrax['codRec'].":00:00:0";

          } else {

            $result = "0:99";

          }
        }
      }

      if($result === ''){
        $result = "0:99";
      } 

      return $result;

    }

    function getTransaction($operatorId, $merchantTxnId){

      // Initializing logger
  		$logger = new Katzgrau\KLogger\Logger(LOG_DIR);

  		// Logging Forgot Password
  		$logger->notice("=== Get Transaction movistarVenezuela ========================================");
  		//$logger->notice("Data from Request:", $merchantTxnId);

      $selproduct =
        "SELECT *
          FROM operator_credentials
          WHERE operator_id = ". $operatorId;
      $resproduct = $this->fireQuery($selproduct);
      $arrproduct = $this->fetchAssoc($resproduct);

      // Assign appropriate values
      $url = $arrproduct['ip_address'];
      $user = $arrproduct['username'];
      $pass = $arrproduct['password'];

      ini_set('default_socket_timeout',15);

      $options = array('connection_timeout'=>15,'exceptions'=>true);

      $result = array();

      try {

        $params = array(
                        'AuthenticationData'=>array(
                                              'Username'=>$user,
                                              'Password'=>$pass
                                          ),
                        'DeviceType'=>3,
                        'Platform'=>1,
                        'ParameterType'=>'ExternalTransactionReference',
                        'ParameterValue'=>$merchantTxnId
                  );
        $logger->notice("Request to Operator:". json_encode($params));

        $client = new SoapClient($url);
        $responseObject = $client->GetTransaction($params);
        $logger->notice("Response of Operator:". json_encode($responseObject));

        $responseArray = json_decode(json_encode($responseObject),TRUE);

        $result['code'] = $responseArray['ResponseCode'];
        $result['desc'] = $responseArray['ResponseMessage'];
        $result['codRec'] = $responseArray['TransactionID'];
        $result['trxCode'] = $responseArray['TransactionResult'];
        $result['trxDate'] = $responseArray['TransactionDate'];

      } catch (Exception $e) {

        $logger->error("Exception from Operator:". json_encode($e));

        if (strpos($e->getMessage(), 'Error Fetching http headers') !== false) {

          $logger->error($e->getMessage().' Timeout from GetTransaction');

          $result['code'] = 0;

        }
      }

      return $result;

    }
  }
?>

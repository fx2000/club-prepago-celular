<?php
/**
 * Send a new Resquest Payment to Instapago Venezuela
 * Club Prepago API
 *
 * @copyright     Club Prepago Celular(tm)
 * @link          http://www.clubprepago.com
 * @package       API.CreditCardProcessors.instapago.php
 * @since         Club Prepago Celular(tm) v 1.1
 */

  class instapago extends Dbconn {

    $selcredentials = "SELECT * FROM cc_processors_credentials WHERE processor_id = 1";
    $rescredentials = $this->fireQuery($selcredentials);
    $arrcredentials = $this->fetchAssoc($rescredentials);

    function  createPayment($data){

      // Initializing logger
  		$logger = new Katzgrau\KLogger\Logger(LOG_DIR);
  		$logger->notice("=== Create Payment Instapago ==========================================");
  		$logger->notice("Data from Request: ", $data);

      // Assign appropriate values
      $url = $arrcredentials['url'];
      $params_array = array(
            'KeyId' => KEY_ID_TDC_PROC,
            'PublicKeyId' => PUBLIC_KEY_ID_TDC_PROC,
            'Amount' => $data['amount'],
            'Description' => 'Pago Servicios de InstaPago',
            'CardHolder' => $data['card_holder'],
            'CardHolderID' => $data['card_holder_id'],
            'CardNumber' => $data['card_number'],
            'CVC' => $data['card_cvc'],
            'ExpirationDate' => $data['card_exp_date'],
            'StatusId' => '1',
            'IP' => $data['ip_address']
      );

      if ($data['transaction_id']) {
        array_push($params_array, 'OrderNumber' => $data['transaction_id']);
      }
      if ($data['cc_address']) {
        array_push($params_array, 'City' => $data['cc_city']);
      }
      if ($data['cc_city']) {
        array_push($params_array, 'OrderNumber' => $data['transaction_id']);
      }
      if ($data['cc_zipcode']) {
        array_push($params_array, 'ZipCode' => $data['cc_zipcode']);
      }
      if ($data['cc_state']) {
        array_push($params_array, 'State' => $data['cc_state']);
      }

      $logger->notice("Parameters to Instapago:", $params_array);

      $params = http_build_query($param_array);
      $header = array(
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded"
      );

      $curl = curl_init();

      curl_setopt_array(
        $curl, array(
          CURLOPT_URL => $url."/payment",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $params,
          CURLOPT_HTTPHEADER => $header,
        )
      );

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      $responseArray = json_decode($response,TRUE);

      $success = $responseArray['success'];
      $id = $responseArray['id'];
      $reference = $responseArray['reference'];

      if ($err) {

        $result = "0".":"."".":"."";

      } else {

        if ($success) {

            $result = "1".":".$id.":".$reference;

        } else {

          $result = "2".":".$id.":".$reference;

        }

      }

      return $result;

    }

    function  completePayment($data){

      // Initializing logger
  		$logger = new Katzgrau\KLogger\Logger(LOG_DIR);

  		// Logging Forgot Password
  		$logger->notice("=== Complete Payment Instapago ==========================================");
  		$logger->notice("Data from Request: ", $data);

      // Assign appropriate values
      $url = $arrcredentials['url'];

      $params_array = array(
            'KeyId' => $arrcredentials['key_id'],
            'PublicKeyId' => $arrcredentials['public_key_id'],
            'Id' => $data['id'],
            'Amount' => $data['amount']
      );

      $params = http_build_query($param_array);
      $header = array(
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded"
      );

      $curl = curl_init();

      curl_setopt_array(
        $curl, array(
          CURLOPT_URL => $url."/complete",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $params,
          CURLOPT_HTTPHEADER => $header,
        )
      );

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      $responseArray = json_decode($response,TRUE);

      $success = $responseArray['success'];
      $id = $responseArray['id'];
      $reference = $responseArray['reference'];

      $result = $success.":".$id.":".$reference;

      return $result;

    }

    function  deletePayment($data){

      // Initializing logger
  		$logger = new Katzgrau\KLogger\Logger(LOG_DIR);

  		// Logging Forgot Password
  		$logger->notice("=== Delete Payment Instapago ==========================================");
  		$logger->notice("Data from Request: ", $data);

      // Assign appropriate values
      $url = $arrcredentials['url'];

      $params_array = array(
            'KeyId' => $arrcredentials['key_id'],
            'PublicKeyId' => $arrcredentials['public_key_id'],
            'Id' => $data['id']
      );

      $params = http_build_query($param_array);
      $header = array(
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded"
      );

      $curl = curl_init();

      curl_setopt_array(
        $curl, array(
          CURLOPT_URL => $url."/complete",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "DELETE",
          CURLOPT_POSTFIELDS => $params,
          CURLOPT_HTTPHEADER => $header,
        )
      );

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      $responseArray = json_decode($response,TRUE);

      $success = $responseArray['success'];
      $id = $responseArray['id'];
      $reference = $responseArray['reference'];

      $result = $success.":".$id.":".$reference;

      return $result;

    }

    function  getPayment($data){

      // Initializing logger
  		$logger = new Katzgrau\KLogger\Logger(LOG_DIR);

  		// Logging Forgot Password
  		$logger->notice("=== Get Payment Instapago ==========================================");
  		$logger->notice("Data from Request: ", $data);

      // Assign appropriate values
      $url = $arrcredentials['url'];

      $params_array = array(
            'KeyId' => $arrcredentials['key_id'],
            'PublicKeyId' => $arrcredentials['public_key_id'],
            'Id' => $data['id']
      );

      $params = http_build_query($param_array);
      $header = array(
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded"
      );

      $curl = curl_init();

      curl_setopt_array(
        $curl, array(
          CURLOPT_URL => $url."/complete",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_POSTFIELDS => $params,
          CURLOPT_HTTPHEADER => $header,
        )
      );

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      $responseArray = json_decode($response,TRUE);

      $success = $responseArray['success'];
      $id = $responseArray['id'];
      $reference = $responseArray['reference'];

      if ($err) {

        $result = "0".":"":"."";

      } else {

        if ($success) {

          $result = $success.":".$id.":".$reference;

        } else {

          $result = $success.":".$id.":".$reference;

        }

      }

      return $result;

    }

  }
?>

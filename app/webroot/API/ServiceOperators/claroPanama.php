<?php
/**
 * Send a new Recharge to Claro Panama
 * Club Prepago API
 *
 * @copyright     Club Prepago Celular(tm)
 * @link          http://www.clubprepago.com
 * @package       API.ServiceOperators.claroPanama.php
 * @since         Club Prepago Celular(tm) v 1.1
 */

  class claroPanama extends Dbconn {

    function doRecharge($data){

      // Initializing logger
  		$logger = new Katzgrau\KLogger\Logger(LOG_DIR);

  		// Logging Forgot Password
  		$logger->notice("=== Recharge claroPanama ==========================================");
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
      $amount = $data['Amount'];
      $phone = $data['Phone_Number'];

      $logger->notice("Operator Credentials:". $url." ".$user." ".$pass);

      $options = array('cache_wsdl'=>WSDL_CACHE_NONE,
                       'connection_timeout'=>15,
                       'exceptions'=>true);

      try {

        $params = array(
                        'parPeticion'=>array(
                                            'Monto'=>$amount,
                                            'Numero_Celular'=>$phone,
                                            'reqLogin'=>array(
                                                              'Clave'=>$pass,
                                                              'Usuario'=>$user
                                                            )
                                        )
                  );
        $logger->notice("Request to Operator:". json_encode($params));

        $client = new SoapClient($url, $options);
        $responseObject = $client->AplicarPagoLineaPrePago($params);
        $logger->notice("Response of Operator:". json_encode($responseObject));

        $responseArray = json_decode(json_encode($responseObject),TRUE);

        $descripcion = $responseArray['AplicarPagoLineaPrePagoResult']['Descripcion'];
        $estado = $responseArray['AplicarPagoLineaPrePagoResult']['Estado'];
        $saldoAct = $responseArray['AplicarPagoLineaPrePagoResult']['SaldoActualizado_Acumulado'];
        $fechaVen = $responseArray['AplicarPagoLineaPrePagoResult']['FechaVencimientoSaldo'];
        $chars = array(":","-","T");
        $fechaVen = substr_replace($chars,"",$fechaVen);
        $phoneno = $responseArray['AplicarPagoLineaPrePagoResult']['NumeroCelular'];
        $codRec = $responseArray['AplicarPagoLineaPrePagoResult']['cod_recarga'];
        $saldo = $responseArray['AplicarPagoLineaPrePagoResult']['saldo'];

      } catch (Exception $e) {

         $result = "0:99";
         $logger->error("Exception from Operator:". json_encode($e));

      }

      if ($estado == 'Exitoso') {

        $result = "0:00:".$codRec.":".$saldoAct.":".$fechaVen.":0";

      } else {

        $result = "0:99";

      }

      return $result;

    }
  }
?>

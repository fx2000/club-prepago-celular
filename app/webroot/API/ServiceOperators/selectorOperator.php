<?php
/**
 * Selector Operators
 *
 * Club Prepago API
 *
 * @copyright     Club Prepago Celular(tm) Project
 * @package       API.ServiceOperators.selectorOperator
 * @since         Club Prepago Celular(tm) v 1.1.0
 */
include "movistarVenezuela.php";

  class selectorOperator {

    function selector($data){

      //Operators Recharge Service Selecction
			switch ($data['Operator']) {
				case 1:
					$opClass = new movistarVenezuela();
					// Excecute recharge
					$rechargeStatus = $opClass->doRecharge($data);
					break;
				default:
					# code...
					break;
			}
      return $rechargeStatus;
    }
  }
?>

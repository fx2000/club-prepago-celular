<?php
/**
 * Selector Operators
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.ServiceOperators.selectorOperator
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "cwPanama.php";
include "digicelPanama.php";
include "claroPanama.php";
//include "movistarPanama.php";

  class selectorOperator {

    function selector($data){

      //Operators Recharge Service Selecction
			switch ($data['Operator']) {
				case 1:
					$opClass = new cwPanama();
					// Excecute recharge
					$rechargeStatus = $opClass->doRecharge($data);
					break;
				case 2:
					$opClass = new digicelPanama();
					// Excecute recharge
					$rechargeStatus = $opClass->doRecharge($data);
					break;
				case 3:
				 	$opClass = new claroPanama();
				 	// Excecute recharge
					$rechargeStatus = $opClass->doRecharge($data);
					break;
				// case 4:
				// 	$opClass = new movistarPanama();
				// 	// Excecute recharge
				// 	$rechargeStatus = $opClass->doRecharge($data);
				// 	break;
				default:
					# code...
					break;
			}
      return $rechargeStatus;
    }
  }
?>

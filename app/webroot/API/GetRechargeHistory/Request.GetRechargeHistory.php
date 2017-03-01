<?php
/**
 * Get Recharge History
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.GetRechargeHistory
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../Dbconn.php";

class RequestGetHistoryAPI extends Dbconn {
	
	/**
	 * Get a user's recharge history
	 */
	function getRechargeHistory($data) {
		$selRechargeHistory =
			"SELECT *
				FROM recharges
				WHERE user_id = " . $data['UserId'];
		$resRechargeHistory = $this->fireQuery($selRechargeHistory);
		$numRechargeHistory = $this->rowCount($resRechargeHistory);
		
		if ($numRechargeHistory > 0) {
			$i = 0;
			
			while ($arrHistory = $this->fetchAssoc($resRechargeHistory)) {
				$rechargeHistory[$i]['HistoryType'] = '1';
				$rechargeHistory[$i]['RechargeStatus'] = $arrHistory['status'];
				$rechargeHistory[$i]['PhoneNo'] = $arrHistory['phone_number'];
				$rechargeHistory[$i]['Operator'] = $arrHistory['operator'];
				$rechargeHistory[$i]['Amount'] = $arrHistory['amount'];
				$rechargeHistory[$i]['PaymentVia'] = $arrHistory['payment_method'];
				$rechargeHistory[$i]['ReferenceNo'] = $arrHistory['merchant_txn_id'];
				$rechargeHistory[$i]['DateTime'] = $arrHistory['recharge_date'];

				// Make sure that trxengine errors are nor transmitted to the final user (this should be done in the app, not here)
				if ($rechargeHistory[$i]['RechargeStatus'] == "1") {
					$rechargeHistory[$i]['Message'] = $arrHistory['response_message'];
				} else {
					$rechargeHistory[$i]['Message'] = "Su recarga ha fallado, por favor intente de nuevo";
				}
				$i++;
			}
			return $rechargeHistory;
		} else {
			return $numRechargeHistory;
		}
	}

	/**
	 * Check User ID
	 */
	function checkUser($userId) {
		$query =
			"SELECT id 
				FROM users
				WHERE id = " . $userId;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
		return $value;
	}

	/**
	 * Check Device ID
	 */
	function checkDevice($deviceId, $platformId, $userId) {
		$query = 
			"SELECT id
				FROM devices
				WHERE device_id = " . $deviceId . " AND user_id = " . $userId . " AND platform_id = " . $platformId . " AND login_status = " . SIGNED_IN;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
		return $value;
	}

	/**
	 * Check Platform ID
	 */
	function checkPlatform($platformId) {
		$query =
			"SELECT id
				FROM platforms
				WHERE id = " . $platformId;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
		return $value;
	}
}

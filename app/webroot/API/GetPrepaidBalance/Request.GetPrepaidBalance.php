<?php
/**
 * Get Prepaid Balance
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.GetPrepaidBalance
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../Dbconn.php";

class RequestGetPrepaidBalanceAPI extends Dbconn {
	
	/**
	 * Get user's prepaid balance
	 */
	function getPrepaidBalance($data) {
		$balance = array();
		$selUser =
			"SELECT balance
				FROM users
				WHERE id = " . $data['UserId'];
		$resUser = $this->fireQuery($selUser);
		$arrUser = $this->fetchAssoc($resUser);
		$balance['PrepaidBalance'] = $arrUser['balance'];
		return $balance;
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

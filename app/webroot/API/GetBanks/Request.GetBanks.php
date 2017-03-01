<?php
/**
 * Get list of banks available for direct deposit
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.GetBanks
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../Dbconn.php";

class RequestGetBanksAPI extends Dbconn {
	
	/*
	 * Get list of active banks
	 */
	function getBanks($data) {
		$banks = array();

		// Select banks from banks table
		$selBanks =
			"SELECT id, bank_name
				FROM banks
				WHERE delete_status = " . NOT_DELETED;
		$resBanks = $this->fireQuery($selBanks);
		$numBanks = $this->rowCount($resBanks);
		
		// If there are banks on the list, return them all
		if ($numBanks > 0) {	
			$i = 0;
			
			while ($arrBank = $this->fetchAssoc($resBanks)) {
				$banks[$i]['id'] = $arrBank['id'];
				$banks[$i]['bank_name'] = $arrBank['bank_name'];
				$i++;
			}
			return $banks;
		} else {
			return $numBanks;
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

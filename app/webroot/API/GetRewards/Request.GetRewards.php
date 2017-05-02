<?php
/**
 * Get Rewards
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.GetRewards
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../Dbconn.php";

class RequestGetRewardsAPI extends Dbconn {
	
	/**
	 * Get Rewards
	 */
	function getRewards($data) {
		
		// Prepare the results array
		$rewards = array();

		// Select data from rewards table
		$selRewards =
			"SELECT *
				FROM rewards
				WHERE status = " . ACTIVE;
				" ORDER BY points asc";
		$resRewards = $this->fireQuery($selRewards);
		$numReward = $this->rowCount($resRewards);

		// If rewards exist, get their information
		if ($numReward > 0) {
			$num = 0;
			
			while ($arrReward = $this->fetchAssoc($resRewards)) {
					$rewards[$num]['Point'] = $arrReward['points'];	
					$rewards[$num]['Cost'] = $arrReward['value'];	
					$rewards[$num]['Description'] = $arrReward['description'];
					$rewards[$num]['Image'] = DOMAINURL . 'img/rewards/' . $arrReward['image'];
					$num++;
			}
			
			// If there is data in the rewards array, return it
			if (!empty($rewards)) {
				return $rewards;

			// Otherwise, return 0
			} else {
				return 0;
			}

		// Otherwise, return 0
		} else {
			return 0;
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
				WHERE device_id = " . $deviceId . " AND user_id = " . $userId . " AND login_status = " . SIGNED_IN;
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

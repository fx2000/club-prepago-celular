<?php
/**
 * Remove Favorite
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.RemoveFavorite
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../Dbconn.php";

class RequestRemoveFavoriteAPI extends Dbconn {
	
	/*
	 * Remove a Favorite from the user's list
	 */
	function removeFavorite($data) {
		$delFavorite =
			"UPDATE favorites
				SET delete_status = " . DELETED .
				" WHERE id = " . $data['FavoriteId'];
		$resFavorite = $this->fireQuery($delFavorite);
		return $resFavorite;
	}

	/*
	 * Check that favorite exists
	 */
	function checkFavorite($userId, $contactId) {
		$query =
			"SELECT id
				FROM favorites
				WHERE  user_id = " . $userId . " AND id = " . $contactId;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
		return $value;
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

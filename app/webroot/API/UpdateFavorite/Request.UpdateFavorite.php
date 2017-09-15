<?php
/**
 * Update Favorite
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.UpdateFavorite
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../../APIConfig/Dbconn.php";

class RequestUpdateFavoriteAPI extends Dbconn {

	/*
	 * What does this function do?
	 */
	function updateFavorite($data) {
		$resultArray = array();
		$selFavorites =
			"UPDATE favorites
				SET name = " . "\"" . $data['Name'] . "\"" . "," .
					" operator = " . $data['Operator'] . "," .
					" phone_number = " . "\"" . $data['Phone_Number'] . "\"" .
				" WHERE id = " . $data['FavoriteId'];
		$resFavorites = $this->fireQuery($selFavorites);

		if ($resFavorites) {
			return 1;
		} else {
			return 0;
		}
	}

	/*
	 * Check if phone number is already in Favorites
	 */
	function checkPhoneNumber($phoneNumber, $userId, $favoriteId) {
		$query =
			"SELECT id
				FROM favorites
				WHERE phone_number = " . "\"" . $phoneNumber . "\"" . " AND user_id = " . $userId . " AND id != " . $favoriteId . " AND delete_status = " . NOT_DELETED;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
		return $value;
	}

	/*
	 * C>heck if Favorite exists
	 */
	function checkFavoriteId($favoriteId) {
		$query =
			"SELECT id
				FROM favorites
				WHERE id = " . $favoriteId . " AND delete_status = " . NOT_DELETED;
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

	/**
	 * Check Mobile Operator
	 */
	function checkOperator($operatorId) {
		$query =
			"SELECT id
				FROM operators
				WHERE id = " . $operatorId;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
		return $value;
	}
}

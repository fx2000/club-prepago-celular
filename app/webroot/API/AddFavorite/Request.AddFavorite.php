<?php
/**
 * Add favorite number
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.AddFavorite
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../Dbconn.php";

class RequestAddFavoriteAPI extends Dbconn {

	/**
	 * Add a new record to the favorites list
	 */
	function addFavorite($data) {

		// Set favorite details
		$userId = $data['UserId'];
		$name = $data['Name'];
		$phoneNumber = $data['Phone_Number'];
		$operator = $data['Operator'];

		// Insert into favorites table
		$reqFavorite =
			"INSERT INTO favorites (
				user_id,
				name,
				phone_number,
				operator
			) VALUES (" .
				$userId . "," .
				"\"" . $name . "\"" . "," .
				$phoneNumber . "," .
				$operator . ")";
		$resFavorite = $this->fireQuery($reqFavorite);

		// If everything goes well, return new Favorite's id
		if ($resFavorite) {
			$favoriteId['FavoriteId'] = mysqli_insert_id($this->_conn);
			return $favoriteId;

		// Otherwise return 0
		} else {
			return 0;
		}
	}

	/**
	 * Check if favorite number already exists
	 */
	function checkFavorite($phoneNumber, $userId) {
		$query =
			"SELECT id
				FROM favorites
				WHERE phone_number = " . "\"" . $phoneNumber . "\"" . " AND user_id = " .  "\"" . $userId . "\"" . " AND delete_status = " . NOT_DELETED;
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

<?php
/**
 * Update Profile
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.UpdateProfile
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../Dbconn.php";

class RequestUpdateProfileAPI extends Dbconn {

	/*
	 * Update a user's profile
	 */
	function updateProfile($data) {
		$query =
			"UPDATE users
				SET address = " . "\"" . $data['Address'] . "\"" . "," .
				"email = " . "\"" . $data['Email'] . "\"" . "," .
				"phone_number = " . "\"" . $data['Phone_Number'] . "\"" . "," .
				"city =" . "\"" . $data['City'] . "\"" . "," .
				"state =" . "\"" . $data['Province'] . "\"" .
				"WHERE id =" . $data['UserId'];
		$result = $this->fireQuery($query);
		return $result;	
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

	/*
	 * Check if email address is assigned to a valid user
	 */
	function checkEmail($email, $userId) {
		$query =
			"SELECT id
				FROM users
				WHERE email = \"" . $email . "\"" . " AND id !=" . $userId . " AND delete_status = " . NOT_DELETED;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
		return $value;
	}
}

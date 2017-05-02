<?php
/**
 * Change Password
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.ChangePassword
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../Dbconn.php";

class RequestChangePasswordAPI extends Dbconn {

	/*
	 * Insert new password into users table
	 */
	function changePassword($data) {
		$selPassword =
			"UPDATE users
				SET password = \"" . sha1($data['NewPassword'].SALT) . "\" WHERE id = " . $data['UserId'];
		$resPassword = $this->fireQuery($selPassword);

		// If everything goes well, return 1
		if ($resPassword) {
			return 1;
		
		// Otherwise return 0
		} else {
			return 0;
		}
	}

	/*
	 * Verify current password
	 */
	function checkCurrentPassword($currentPassword, $userId) {
		$query =
			"SELECT id
				FROM users
				WHERE id = " . $userId . " AND password = \"" . sha1($currentPassword.SALT) . "\"";
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

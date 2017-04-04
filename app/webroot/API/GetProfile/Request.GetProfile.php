<?php
/**
 * Get Profile
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.GetProfile
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../Dbconn.php";

class RequestGetProfileAPI extends Dbconn {
	
	/**
	 * Get profile information
	 */
	function getProfile($data) {

		// Select data from users table
		$selUser =
			"SELECT u.*,c.name As country
				FROM users As u,countries AS c
				WHERE c.id = u.country_id AND u.id = \"" .
			$data['UserId'] . "\" ";
		$resUser = $this->fireQuery($selUser);
		$arrUser = $this->fetchAssoc($resUser);

		// Get sponsor's name
		$selSponsor =
			"SELECT *
				FROM sponsors
				WHERE id = " . $arrUser['sponsor_id'];
		$resSponsor = $this->fireQuery($selSponsor);
		$arrSponsor = $this->fetchAssoc($resSponsor);

		// Fill response array
		$users['Name'] = $arrUser['name'];
		$users['ID'] = $arrUser['tax_id'];
		$users['Email'] = $arrUser['email'];
		$users['Address'] = $arrUser['address'];
		$users['Country'] = $arrUser['country'];
		$users['City'] = $arrUser['city'];
		$users['Province'] = $arrUser['state'];
		$users['PhoneNo'] = $arrUser['phone_number'];

		// Fill the User ID with zeroes for cosmetic reasons
		$remaining = 6 - strlen($arrUser['id']);
		$userId = '';
		
		for ($i = 0; $i < $remaining; $i++) {
			$userId .= '0';
		}
		$userId .= $arrUser['id'];

		// Continue filling the response array
		$users['UserId'] = $userId;

		// Fill the Sponsor ID with zeroes for cosmetic reasons
		$remaining2 = 6 - strlen($arrSponsor['id']);
		$sponsorId = '';
		
		for ($i = 0; $i < $remaining2; $i++) {
			$sponsorId .= '0';
		}
		$sponsorId .= $arrSponsor['id'];

		// Finish filling the response array
		$users['SponsorId'] = $sponsorId;
		$users['SponsorName'] = $arrSponsor['name'];

		//Return profile information
		return $users;
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
				WHERE device_id = " . $deviceId . " AND user_id = " . $userId . " 
				AND login_status = " . SIGNED_IN;
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

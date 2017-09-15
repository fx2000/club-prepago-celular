<?php
/**
 * Get a user's Favorites
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.GetFavorites
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../../APIConfig/Dbconn.php";

class RequestGetFavoritesAPI extends Dbconn {

	/**
	 * Get list of favorites
	 */
	function getFavorites($data) {
		$favorites = array();

		// Select favorites from favorites table
		$selFavorites =
			"SELECT *
				FROM favorites
				WHERE user_id = " . $data['UserId'] . " AND delete_status = ". NOT_DELETED;
		$resFavorites = $this->fireQuery($selFavorites);
		$numFavorites = $this->rowCount($resFavorites);

		// If there are favorites on the list, return them all
		if ($numFavorites > 0) {
			$i = 0;

			while ($arrFavorites = $this->fetchAssoc($resFavorites)) {
				$favorites[$i]['id'] = $arrFavorites['id'];
				$favorites[$i]['name'] = $arrFavorites['name'];
				$favorites[$i]['phone_number'] = $arrFavorites['phone_number'];
				$favorites[$i]['operator'] = $arrFavorites['operator'];
				$i++;
			}
			return $favorites;
		} else {
			return $numFavorites;
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

<?php
/**
 * Get Settings
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.GetSettings
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../../APIConfig/Dbconn.php";

class RequestGetSettingsAPI extends Dbconn {

	/*
	 * Get Settings
	 */
	function getSettings($data) {

		// Get data from settings table
		$selSettings =
			"SELECT *
				FROM settings ";
		$resSettings = $this->fireQuery($selSettings);
		$numSettings = $this->rowCount($resSettings);

		// If data is present, return settings
		if ($numSettings > 0) {
			$arrSettings = $this->fetchAssoc($resSettings);
			$response['credit_card_fee_percent'] = $arrSettings['credit_card_fee_percent'];
			$response['credit_card_fee_fixed'] = $arrSettings['credit_card_fee_fixed'];
			return $response;

		// Otherwise, return 0
		} else {
			return $numSettings;
		}
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

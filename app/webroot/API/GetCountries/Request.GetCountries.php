<?php
/**
 * Country
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.Country
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../Dbconn.php";

class RequestGetCountriesAPI extends Dbconn {

	/*
	 * Get list of countries
	 */
	function getCountries($data) {
		$countries = array();

		// Select countries from countries table
		$selCountries =
			"SELECT *
				FROM countries";
		$resCountries = $this->fireQuery($selCountries);
		$numCountries = $this->rowCount($resCountries);

		// If there are countries on the list, return them all
		if ($numCountries > 0) {
			$i = 0;
			
			while ($arrCountries = $this->fetchAssoc($resCountries)) {
				$countries[$i]['Id'] = $arrCountries['id'];
				$countries[$i]['Name'] = $arrCountries['name'];
				$i++;
			}
			return $countries;
		} else {
			return $numCountries;
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

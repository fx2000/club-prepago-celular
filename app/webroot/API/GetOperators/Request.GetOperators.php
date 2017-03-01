
<?php
/**
 * Get Mobile Operators
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.GetOperators
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../Dbconn.php";

class RequestGetOperatorsAPI extends Dbconn {
	
	/*
	 * Get list of Mobile Operators
	 */
	function getOperators($data) {
		$operators = array();

		// Select active mobile operators from operators table
		$selOperators =
			"SELECT *
				FROM operators
				WHERE status = ". ACTIVE;
		$resOperators = $this->fireQuery($selOperators);	
		$numOperators = $this->rowCount($resOperators);

		// If there are mobile operators on the list, return them all
		if ($numOperators > 0) {
			$i = 0;
			
			while ($arrOperators = $this->fetchAssoc($resOperators)) {
				$operators[$i]['id'] = $arrOperators['id'];
				$operators[$i]['name'] = $arrOperators['name'];
				$i++;
			}
			return $operators;
		} else {
			return $numOperators;
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

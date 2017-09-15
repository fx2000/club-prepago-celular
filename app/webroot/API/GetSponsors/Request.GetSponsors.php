<?php
/**
 * Get list of Sponsors
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.GetSponsor
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../../APIConfig/Dbconn.php";

class RequestGetSponsorsAPI extends Dbconn {

	/**
	 * What does this function do?
	 */
	function getSponsors($data) {
		$sponsors = array();
		$selSponsors =
			"SELECT *
				FROM sponsors
				WHERE status = " . ACTIVE . " AND delete_status = " . NOT_DELETED;
		$resSponsors = $this->fireQuery($selSponsors);
		$numSponsors = $this->rowCount($resSponsors);

		if ($numSponsors > 0) {
			$i = 0;

			while ($ArrSponsors = $this->fetchAssoc($resSponsors)) {
				$sponsors[$i]['id'] = $ArrSponsors['id'];
				$sponsors[$i]['name'] = $ArrSponsors['name'];
				$i++;
			}
			return $sponsors;
		} else {
			return $numSponsors;
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

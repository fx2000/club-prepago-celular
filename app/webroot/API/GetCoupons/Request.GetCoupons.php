<?php
/**
 * Get Coupons
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.GetCoupons
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../../APIConfig/Dbconn.php";

class RequestGetCouponsAPI extends Dbconn {

  /**
	 * Get Coupons
	 */
	function getCoupons($data) {

    // Prepare the results array
		$coupons = array();

    // Select data from coupons table
		$selCoupons =
			"SELECT *
				FROM coupons
				WHERE status = " . ACTIVE;
				" ORDER BY coupon_type asc";
		$resCoupons = $this->fireQuery($selCoupons);
		$numCoupon = $this->rowCount($resCoupons);

    // If coupons exist, get their information
		if ($numCoupon > 0) {

      $num = 0;

			while ($arrCoupon = $this->fetchAssoc($resCoupons)) {

          $coupons[$num]['id'] = $arrCoupon['id'];
					$coupons[$num]['Amount'] = $arrCoupon['amount'];
					$coupons[$num]['Duedate'] = $arrCoupon['due_date'];
					$coupons[$num]['Description'] = $arrCoupon['description'];
					//$coupons[$num]['Image'] = DOMAINURL . 'img/rewards/' . $arrCoupon['image'];
          $coupons[$num]['Image'] = $arrCoupon['image'];
					$num++;

      }

      // If there is data in the coupon array, return it
			if (!empty($coupons)) {

				return $coupons;

			// Otherwise, return 0
			} else {

				return 0;

      }

      // Otherwise, return 0
  		} else {

  			return 0;

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
  	function checkDevice($deviceId, $userId) {
  		$query =
  			"SELECT id
  				FROM devices
  				WHERE device_id = " . $deviceId . " AND user_id = " . $userId . " AND login_status = " . SIGNED_IN;
  		$result = $this->fireQuery($query);
  		$value = $this->rowCount($result);
  		return $value;
  	}
}

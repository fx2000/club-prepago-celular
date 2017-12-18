<?php
/**
 * Get Coupon History
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.GetCouponHistory
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../../APIConfig/Dbconn.php";

class RequestGetCouponHistoryAPI extends Dbconn {

	/**
	 * Get user's coupon history
	 */
	function getCouponHistory($data) {

    $couponHistory = array();
		$userId = $data['UserId'];
		$userData = $this->getUserData($userId);
    $userType = $userData['user_type'];

		//Select coupon from coupon_redemptions table

		if($userType == 3){ //Store User

			$selcouponHistory = "SELECT cr.coupon_id, cr.status, cr.reference_no, u.name, c.amount, c.points, cr.purchase_date,
													  			cr.redeem_date, c.due_date, c.description, c.image
													 FROM coupons c, coupon_redemptions cr, users_stores us, users u
													 WHERE c.id = cr.coupon_id AND c.store_id = us.store_id AND cr.user_id = u.id
													 AND us.user_id = ". $userId ." ORDER BY cr.status, cr.purchase_date";

		} else {

			$selcouponHistory = "SELECT cr.coupon_id, cr.status, cr.reference_no, '' AS name, c.amount, c.points, cr.purchase_date,
																	cr.redeem_date, c.due_date, c.description, c.image
													 FROM coupons c, coupon_redemptions cr
													 WHERE c.id = cr.coupon_id AND cr.user_id = ". $userId . " ORDER BY cr.status, cr.purchase_date";

		}

		$rescouponHistory = $this->fireQuery($selcouponHistory);
		$numcouponHistory = $this->rowCount($rescouponHistory);

		// If there are coupons on the list, return them all
		if ($numcouponHistory > 0) {

      $i = 0;

			while ($arrcouponHistory = $this->fetchAssoc($rescouponHistory)) {

				$couponHistory[$i]['id'] = $arrcouponHistory['coupon_id'];
				$couponHistory[$i]['status'] = $arrcouponHistory['status'];
				$couponHistory[$i]['reference_no'] = str_pad($arrcouponHistory['reference_no'],6,"0",STR_PAD_LEFT);
				$couponHistory[$i]['name'] = ($arrcouponHistory['name']);
				$couponHistory[$i]['amount']	= $arrcouponHistory['amount'];
        $couponHistory[$i]['points']	= $arrcouponHistory['points'];
				$couponHistory[$i]['purchase_date'] = $arrcouponHistory['purchase_date'];
        $couponHistory[$i]['redeem_date']	= $arrcouponHistory['redeem_date'];
        $couponHistory[$i]['due_date']	= $arrcouponHistory['due_date'];
        $couponHistory[$i]['description']	= $arrcouponHistory['description'];
        $couponHistory[$i]['image']	= $arrcouponHistory['image'];
				$i++;

			}

      return $couponHistory;

    } else {

      return $numcouponHistory;
		}

  }

	/**
	 * Check User ID
	 */
	function checkUser($userId) {

    $query = "SELECT id FROM users WHERE id = " . $userId;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);

    return $value;

  }

  /**
	 * Check Device ID
	 */
	function checkDevice($deviceId, $userId) {

    $query = "SELECT id FROM devices WHERE device_id = " . $deviceId . " AND user_id = " . $userId .
						 " AND login_status = " . SIGNED_IN;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
		return $value;
	}

	/**
	 * Get user's data
	 */
	function getUserData($userId) {

		$query = "SELECT * FROM users WHERE id = " . $userId . " AND status = 1 AND delete_status = 0";
		$result = $this->fireQuery($query);
		$value = $this->fetchAssoc($result);
		return $value;

  }
}

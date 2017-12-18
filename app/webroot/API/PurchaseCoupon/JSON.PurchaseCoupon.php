<?php
/**
 * Purchase Coupon
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.Purchase Coupon
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "Request.PurchaseCoupon.php";
include "../../APIConfig/ServerStatusCodes.php";

class RestResponse {

	/**
	 * Generate JSON response
	 */
	function generateResponse($CLIENT_DATA_ARY) {

		// Initializing logger
		//$logger = new Katzgrau\KLogger\Logger(LOG_DIR);

		// Logging Forgot Password
		//$logger->info("============================================================");
		//$logger->info("Received PurchaseCoupon request:", $CLIENT_DATA_ARY);

		$returnArray = array();
		$responseArray = array();
		$client_key_array = array();
		$check_data_array = array(
			'0' => 'UserId',
			'1' => 'DeviceId',
			'2' => 'CouponId',
      '3' => 'Latitude',
      '4' => 'Longitude'
		);

		// Check if the correct parameters are being sent and mark as (S)uccess or (F)ailed
		foreach ($CLIENT_DATA_ARY as $key => $val) {

			array_push($client_key_array, $key);

    }

		for ($i = 0; $i < count($client_key_array); $i++) {

			if (in_array($client_key_array[$i], $check_data_array)) {

        array_push($returnArray, 'S');

      } else {

        array_push($returnArray, 'F');

      }
		}

		// If parameter check fails, send an error message
		if (in_array("F", $returnArray)) {

      //$logger->error("PurchaseCoupon request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('501'));

      return $this->generateJSONError('501');

    } else {

			// Check User ID
			if (in_array("UserId", $client_key_array)) {

				$userId = $CLIENT_DATA_ARY['UserId'];

				if (strlen($userId) == 0) {

					//$logger->error("PurchaseCoupon request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('541'));
					return $this->generateJSONError('541');

        } else if (!preg_match("/^[0-9\+]+$/i", stripslashes($userId))) {

          //$logger->error("PurchaseCoupon request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('542'));
					return $this->generateJSONError('542');
				}

			} else {

        //$logger->error("PurchaseCoupon request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('540'));
				return $this->generateJSONError('540');

      }

			// Check Device ID
			if (in_array("DeviceId", $client_key_array)) {

				$deviceId = $CLIENT_DATA_ARY['DeviceId'];

				if (strlen($deviceId) == 0) {

					//$logger->error("PurchaseCoupon request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('503'));
					return $this->generateJSONError('503');

        }

      } else {

        //$logger->error("PurchaseCoupon request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('502'));
				return $this->generateJSONError('502');

      }

			// Check Coupon ID
			if (in_array("CouponId", $client_key_array)) {

				$couponId = $CLIENT_DATA_ARY['CouponId'];

				if (strlen($couponId) == 0) {

					//$logger->error("PurchaseCoupon request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('541'));
					return $this->generateJSONError('718');

        } else if (!preg_match("/^[0-9\+]+$/i", stripslashes($couponId))) {

          //$logger->error("PurchaseCoupon request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('542'));
					return $this->generateJSONError('719');
				}

			} else {

        //$logger->error("PurchaseCoupon request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('540'));
				return $this->generateJSONError('717');

      }

			// If all fields are validated correctly, call the PurchaseCoupon API
			$REQ_SUCCESS = new RequestPurchaseCouponAPI();

			// Check that User ID is valid
			$userIdValid = $REQ_SUCCESS->checkUser($userId);

			if ($userIdValid == 0) {

        //$logger->error("GetPrepaidBalance request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('543'));
				return $this->generateJSONError('543');
			}

			// Check that Device ID is valid
			$deviceIdValid = $REQ_SUCCESS->checkDevice($deviceId, $userId);

			if ($deviceIdValid == 0) {

        //$logger->error("PurchaseCoupon request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('544'));
				return $this->generateJSONError('544');

      }

      // Check that Coupon is valid
			$couponValid = $REQ_SUCCESS->checkCoupon($couponId);

			if ($couponValid == 0) {

        //$logger->error("Purchase Coupon request failed coupon " . $CLIENT_DATA_ARY['CouponId'] . " " . $this->generateJSONError('554'));
				return $this->generateJSONError('723');

      }

      // Check that the user has enough available balance
			$balanceCheck = $REQ_SUCCESS->checkBalance($userId);
      $couponData = $REQ_SUCCESS->getCouponData($couponId);

			if ($balanceCheck['balance'] < $couponData['amount']) {

        //$logger->error("Purchase Coupon request failed for Coupon " . $CLIENT_DATA_ARY['CouponId'] . " " . $this->generateJSONError('589'));
				return $this->generateJSONError('589');

      }

			// Purchase Coupon
			$purchaseCoupon = $REQ_SUCCESS->purchaseCoupon($CLIENT_DATA_ARY);

			if ($purchaseCoupon != 0) {

				$status = '533';
				$obj_server_RespCode_code = new ServerStatusCode();
				$output = $obj_server_RespCode_code->getStatusCodeMessage($status);
				$arr['Status'] = '1';
				$arr['Code'] = $status;
				$arr['Message'] = $output;
				$arr['Data'] = $purchaseCoupon['Data'];
				$result['Response'] = $arr;

				//$logger->notice("PurchaseCoupon request successful for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . json_encode($result));
				return json_encode($result);

			} else {

				//$logger->error("PurchaseCoupon request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('610'));
				return $this->generateJSONError('716');

			}
		}
	}

	/**
	 * Generate JSON error
	 */
	function generateJSONError($status) {
		$obj_server_RespCode_code = new ServerStatusCode();
		$output = $obj_server_RespCode_code->getStatusCodeMessage($status);
		$arr['Status'] = '0';
		$arr['Code'] = $status;
		$arr['Message'] = $output;
		$result['Response'] = $arr;
		return json_encode($result);
	}
}

// Send response back to user
$POSTDATA = $_POST;
$obj = new RestResponse();
echo $obj->generateResponse($POSTDATA);

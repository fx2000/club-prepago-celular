<?php
/**
 * Send Credit Card Payment
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.SendPaymentCreditCard
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "Request.SendPaymentCreditCard.php";
include "../../APIConfig/ServerStatusCodes.php";

class RestResponse {

	/**
	 * Generate JSON response
	 */
	function generateResponse($CLIENT_DATA_ARY) {

		// Initializing logger
		$logger = new Katzgrau\KLogger\Logger(LOG_DIR);

		// Logging Forgot Password
		$logger->notice("============================================================");
		$logger->notice("Received SendPaymentCreditCard request:", $CLIENT_DATA_ARY);

		$returnArray = array();
		$client_key_array = array();
		$check_data_array = array(
			'0' => 'UserId',
			'1' => 'DeviceId',
			'2' => 'PlatformId',
			'3'	=> 'amount',
			'4'	=> 'discount_rate',
			'5'	=> 'tax_rate',
			'6'	=> 'TransactionId',
			'7'	=> 'TransactionStatus',
			'8' => 'latitude',
			'9' => 'longitude'
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
			$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('501'));
			return $this->generateJSONError('501');

		// Otherwise, check each parameter's validity individually
		} else {

			// Check User ID
			if (in_array("UserId", $client_key_array)) {
				$userId = $CLIENT_DATA_ARY['UserId'];

				if (strlen($userId) == 0) {
					$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('541'));
					return $this->generateJSONError('541');
				} else if (!preg_match("/^[0-9\+]+$/i", stripslashes($userId))) {
					$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('542'));
					return $this->generateJSONError('542');
				}
			} else {
				$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('540'));
				return $this->generateJSONError('540');
			}

			// Check Device ID
			if (in_array("DeviceId", $client_key_array)) {
				$deviceId = $CLIENT_DATA_ARY['DeviceId'];

				if (strlen($deviceId) == 0) {
					$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('503'));
					return $this->generateJSONError('503');
				}
			} else {
				$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('502'));
				return $this->generateJSONError('502');
			}

			// Check Platform ID
			if (in_array("PlatformId", $client_key_array)) {
				$platformId = $CLIENT_DATA_ARY['PlatformId'];

				if (strlen($platformId) == 0) {
					$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('526'));
					return $this->generateJSONError('526');
				}
			} else {
				$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('527'));
				return $this->generateJSONError('527');
			}

			// Check amount
			if (in_array("amount", $client_key_array)) {
				$amount = $CLIENT_DATA_ARY['amount'];

				if (strlen($amount) == 0) {
					$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('601'));
					return $this->generateJSONError('601');
				} else if (!is_int(intval($amount)) && !is_float(intval($amount))) {
					$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('602'));
					return $this->generateJSONError('602');
				}
			} else {
				$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('601'));
				return $this->generateJSONError('601');
			}

			// Check discount
			if (in_array("discount_rate", $client_key_array)) {
				$rateDiscount = $CLIENT_DATA_ARY['discount_rate'];

				if (strlen($rateDiscount) == 0) {
					$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('705'));
					return $this->generateJSONError('705');
				} else if (!is_int(intval($rateDiscount)) && !is_float(intval($rateDiscount))) {
					$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('706'));
					return $this->generateJSONError('706');
				}
			} else {
				$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('704'));
				return $this->generateJSONError('704');
			}

			// Check tax
			if (in_array("tax_rate", $client_key_array)) {
				$rateTax = $CLIENT_DATA_ARY['tax_rate'];

				if (strlen($rateTax) == 0) {
					$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('708'));
					return $this->generateJSONError('708');
				} else if (!is_int(intval($rateTax)) && !is_float(intval($rateTax))) {
					$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('709'));
					return $this->generateJSONError('709');
				}
			} else {
				$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('707'));
				return $this->generateJSONError('707');
			}

			// Check payment processor's transaction ID
			if (in_array("TransactionId", $client_key_array)) {
				$transactionId = $CLIENT_DATA_ARY['TransactionId'];

				if (strlen($transactionId) == 0) {
					$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('603'));
					return $this->generateJSONError('603');
				}
			} else {
				return $this->generateJSONError('603');
			}

			// Check payment processor's transaction status
			if (in_array("TransactionStatus", $client_key_array)) {
				$transactionStatus = $CLIENT_DATA_ARY['TransactionStatus'];

				if (strlen($transactionStatus) == 0) {
					$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('604'));
					return $this->generateJSONError('604');
				} else if ($transactionStatus != 0 && $transactionStatus != 1) {
					$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('605'));
					return $this->generateJSONError('605');
				}
			} else {
				$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('604'));
				return $this->generateJSONError('604');
			}

			// If all fields are validated correctly, call the Send Payment Credit Card API
			$REQ_SUCCESS = new RequestSendPaymentCreditCardAPI();

			// Check that Platform is valid
			$platformValid = $REQ_SUCCESS->checkPlatform($platformId);

			if ($platformValid == 0) {
				$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('527'));
				return $this->generateJSONError('527');
			}

			// Check that User ID is valid
			$userIdValid = $REQ_SUCCESS->checkUser($userId);

			if ($userIdValid == 0) {
				$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('543'));
				return $this->generateJSONError('543');
			}

			// Check that Device ID is valid
			$deviceIdValid = $REQ_SUCCESS->checkDevice($deviceId, $platformId, $userId);

			if ($deviceIdValid == 0) {
				$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('544'));
				return $this->generateJSONError('544');
			}

			// Send credit card payment information
			$sendPayment = $REQ_SUCCESS->sendPaymentCreditCard($CLIENT_DATA_ARY);

			// If everything went well, return result
			if ($sendPayment > 0) {
				$status = '533';
				$obj_server_RespCode_code = new ServerStatusCode();
				$output = $obj_server_RespCode_code->getStatusCodeMessage($status);
				$arr['Status'] = '1';
				$arr['Code'] = $status;
				$arr['Message'] = $output;
				$result['Response'] = $arr;
				$logger->notice("SendPaymentCreditCard request successful for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . json_encode($result));
				return json_encode($result);

			// Otherwise, return appropriate error code
			} else {
				$logger->error("SendPaymentCreditCard request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('606'));
				return $this->generateJSONError('606');
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

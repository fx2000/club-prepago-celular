<?php
/**
 * Recharge
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.Recharge
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "Request.Recharge.php";
include "../ServerStatusCodes.php";

class RestResponse {

	/**
	 * Generate JSON response
	 */
	function generateResponse($CLIENT_DATA_ARY) {

		// Initializing logger
		$logger = new Katzgrau\KLogger\Logger(LOG_DIR);

		// Logging Forgot Password
		$logger->notice("============================================================");
		$logger->notice("Received Recharge request:", $CLIENT_DATA_ARY);

		$returnArray = array();
		$responseArray = array();
		$client_key_array = array();
		$check_data_array = array(
			'0' => 'Phone_Number',
			'1' => 'Operator',
			'2' => 'Amount',
			'3' => 'Payment_Method',
			'4' => 'UserId',
			'5' => 'DeviceId',
			'6' => 'PlatformId',
			'7' => 'latitude',
			'8' => 'longitude'
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
			$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('501'));
			return $this->generateJSONError('501');

		// Otherwise, check each parameter's validity individually
		} else {

			// Check phone number
			if (in_array("Phone_Number", $client_key_array)) {
				$phoneNumber = $CLIENT_DATA_ARY['Phone_Number'];

				if (strlen($phoneNumber) == 0) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('522'));
					return $this->generateJSONError('522');
				} else if (!preg_match("/^[0-9\+]+$/i", stripslashes($phoneNumber))) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('523'));
					return $this->generateJSONError('523');
				}
			} else  {
				$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('521'));
				return $this->generateJSONError('521');
			}

			// Check mobile operator
			if (in_array("Operator", $client_key_array)) {
				$operator = $CLIENT_DATA_ARY['Operator'];

				if (strlen($operator) == 0) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('539'));
					return $this->generateJSONError('539');
				}
			} else  {
				$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('538'));
				return $this->generateJSONError('538');
			}

			// Check amount
			if (in_array("Amount", $client_key_array)) {
				$amount = $CLIENT_DATA_ARY['Amount'];

				if (strlen($amount) == 0) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('601'));
					return $this->generateJSONError('601');
				} else if (!is_int(intval($amount)) && !is_float(intval($amount))) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('602'));
					return $this->generateJSONError('602');
				}
			} else {
				return $this->generateJSONError('601');
			}

			// Check payment method
			if (in_array("Payment_Method", $client_key_array)) {
				$paymentMethod = $CLIENT_DATA_ARY['Payment_Method'];

				if (strlen($paymentMethod) == 0) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('560'));
					return $this->generateJSONError('560');
				} else if (!is_int(intval($paymentMethod))) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('561'));
					return $this->generateJSONError('561');
				}
			} else {
				$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('559'));
				return $this->generateJSONError('559');
			}

			// Check User ID
			if (in_array("UserId", $client_key_array)) {
				$userId = $CLIENT_DATA_ARY['UserId'];

				if (strlen($userId) == 0) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('541'));
					return $this->generateJSONError('541');
				} else if (!preg_match("/^[0-9\+]+$/i", stripslashes($userId))) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('542'));
					return $this->generateJSONError('542');
				}
			} else {
				$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('540'));
				return $this->generateJSONError('540');
			}

			// Check Device ID
			if (in_array("DeviceId", $client_key_array)) {
				$deviceId = $CLIENT_DATA_ARY['DeviceId'];

				if (strlen($deviceId) == 0) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('503'));
					return $this->generateJSONError('503');
				}
			} else {
				$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('502'));
				return $this->generateJSONError('502');
			}

			// Check Platform ID
			if (in_array("PlatformId", $client_key_array)) {
				$platformId = $CLIENT_DATA_ARY['PlatformId'];

				if (strlen($platformId) == 0) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('526'));
					return $this->generateJSONError('526');
				}
			} else {
				$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('527'));
				return $this->generateJSONError('527');
			}

			// If all fields are validated correctly, call the Request Recharge API
			$REQ_SUCCESS = new RequestRechargeAPI();

			// Check that Platform is valid
			$platformValid = $REQ_SUCCESS->checkPlatform($platformId);

			if ($platformValid == 0) {
				$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('527'));
				return $this->generateJSONError('527');
			}

			// Check that User ID is valid
			$userIdValid = $REQ_SUCCESS->checkUser($userId);

			if ($userIdValid == 0) {
				$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('543'));
				return $this->generateJSONError('543');
			}

			// Check that Device ID is valid
			$deviceIdValid = $REQ_SUCCESS->checkDevice($deviceId, $platformId, $userId);

			if ($deviceIdValid == 0) {
				$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('544'));
				return $this->generateJSONError('544');
			}

			// Check that Mobile Operator is valid
			$operatorValid = $REQ_SUCCESS->checkOperator($operator);

			if ($operatorValid == 0) {
				$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('554'));
				return $this->generateJSONError('554');
			}

			// Check that the user has enough available balance
			$balanceCheck = $REQ_SUCCESS->checkBalance($userId);
			$amount = $CLIENT_DATA_ARY['Amount'];

			if ($balanceCheck < $amount) {
				$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . $this->generateJSONError('589'));
				return $this->generateJSONError('589');
			}

			// Excecuting recharge
			$rechargeStatus = $REQ_SUCCESS->recharge($CLIENT_DATA_ARY);

			// If everything went well, save successful response into array
			if ($rechargeStatus['Status'] == 0) {
				$status = $rechargeStatus['Code'];
				$obj_server_RespCode_code = new ServerStatusCode();
				$output = $obj_server_RespCode_code->getStatusCodeMessage($status);
				$arr['Status'] = $rechargeStatus['Status'];
				$arr['Code'] = $status;
				$arr['Message'] = $output;
				$arr['Data'] = $rechargeStatus['Data'];
				$result['Response'] = $arr;

			// Otherwise, save failed response into array
			} else {
				$status = $rechargeStatus['Code'];
				$obj_server_RespCode_code = new ServerStatusCode();
				$output = $obj_server_RespCode_code->getStatusCodeMessage($status);
				$arr['Status'] = $rechargeStatus['Status'];
				$arr['Code'] = $status;
				$arr['Message'] = $output;
				$arr['Data'] = $rechargeStatus['Data'];
				$result['Response'] = $arr;
			}

			// Return results to the user
			$logger->notice("Recharge request completed for Phone Number " . $CLIENT_DATA_ARY['Phone_Number'] . " " . json_encode($result));
			return json_encode($result);
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

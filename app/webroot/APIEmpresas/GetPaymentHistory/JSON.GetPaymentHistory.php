<?php
/**
 * Get Payment History
 *
 * Club Prepago APIEmpresas
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       APIEmpresas.GetPaymentHistory
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../../API/GetPaymentHistory/Request.GetPaymentHistory.php";
include "../../APIConfig/ServerStatusCodes.php";
include "../Login/Request.Login.php";

class RestResponse {

	/**
	 * Generate JSON response
	 */
	function generateResponse($CLIENT_DATA_ARY) {

		// Initializing logger
		$logger = new Katzgrau\KLogger\Logger(LOG_DIR);

		// Logging Forgot Password
		$logger->info("============================================================");
		$logger->info("Received GetPaymentHistory request:", $CLIENT_DATA_ARY);

		$returnArray = array();
		$responseArray = array();
		$client_key_array = array();
		$check_data_array = array(
			'0' => 'UserId',
			'1' => 'Password'
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
		if (in_array("F",$returnArray)) {
			$logger->error("GetOperators request failed " . $this->generateJSONError('501'));
			return $this->generateJSONError('501');
		} else {

			// Check User ID
			if (in_array("UserId", $client_key_array)) {
				$userId = $CLIENT_DATA_ARY['UserId'];
				if (strlen($userId) == 0) {
					$logger->error("GetBanks request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('541'));
					return $this->generateJSONError('541');
				} else if (!preg_match("/^[0-9\+]+$/i", stripslashes($userId))) {
					$logger->error("GetBanks request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('542'));
					return $this->generateJSONError('542');
				}
			} else {
				$logger->error("GetBanks request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('540'));
				return $this->generateJSONError('540');
			}

			// Check password
			if (in_array("Password", $client_key_array)) {
				$password = $CLIENT_DATA_ARY['Password'];

				if (strlen($password) == 0) {
					$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('613'));
					return $this->generateJSONError('613');
				}
			} else {
				$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('612'));
				return $this->generateJSONError('612');
			}

			// If all fields are validated correctly, call the Login API
			$REQ_SUCCESS_LOGIN = new RequestLoginAPI();
			$loginData = $REQ_SUCCESS_LOGIN->login($CLIENT_DATA_ARY);

			// Check for username/password validation
			if ($loginData == 0) {
				$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('535'));
				return $this->generateJSONError('535');

			// Check if user is active
			} else if ($loginData == 1) {
				$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('536'));
				return $this->generateJSONError('536');

			// Check if user has verified their email address
			} else if ($loginData == 2) {
				$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('537'));
				return $this->generateJSONError('537');

			// Check if user is banned
			} else if ($loginData == 3) {
				$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('609'));
				return $this->generateJSONError('609');

			// Send results back
			} else {

				$CLIENT_DATA_ARY['UserId'] = $loginData['UserId'];

				// If all fields are validated correctly, call the Get Payment History API
				$REQ_SUCCESS = new RequestGetPaymentHistoryAPI();
				// Request payment history
				$PaymentHistory = $REQ_SUCCESS->getPaymentHistory($CLIENT_DATA_ARY);

				// If everything went well, return result
				if ($PaymentHistory > 0) {
					$status = '533';
					$obj_server_RespCode_code = new ServerStatusCode();
					$output = $obj_server_RespCode_code->getStatusCodeMessage($status);
					$arr['Status'] = '1';
					$arr['Code'] = $status;
					$arr['Message'] = $output;
					$arr['Data'] = $PaymentHistory;
					$result['Response'] = $arr;
					$logger->info("GetPaymentHistory request successful for UserId " . $userId . " " . json_encode($result));
					return json_encode($result);
				}

				// Otherwise, return appropriate error code
				else {
					$logger->error("GetPaymentHistory request failed for UserId " . $userId . " " . $this->generateJSONError('595'));
					return $this->generateJSONError('595');
				}
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

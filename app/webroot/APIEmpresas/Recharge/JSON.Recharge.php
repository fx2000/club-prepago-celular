<?php
/**
 * Recharge
 *
 * Club Prepago APIEmpresas
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       APIEmpresas.Recharge
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../../API/Recharge/Request.Recharge.php";
//include "../../API/ServiceOperators/selectorOperator.php";
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
		$logger->notice("============================================================");
		$logger->notice("Received Recharge request:", $CLIENT_DATA_ARY);

		$returnArray = array();
		$responseArray = array();
		$client_key_array = array();
		$check_data_array = array(
			'0' => 'UserId',
			'1' => 'Password',
			'2' => 'PhoneNumber',
			'3' => 'Operator',
			'4' => 'Amount'
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
			$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['PhoneNumber'] . " " . $this->generateJSONError('501'));
			return $this->generateJSONError('501');

		// Otherwise, check each parameter's validity individually
		} else {

			// Check User ID
			if (in_array("UserId", $client_key_array)) {
				$userId = $CLIENT_DATA_ARY['UserId'];

				if (strlen($userId) == 0) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['PhoneNumber'] . " " . $this->generateJSONError('541'));
					return $this->generateJSONError('541');
				} else if (!preg_match("/^[0-9\+]+$/i", stripslashes($userId))) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['PhoneNumber'] . " " . $this->generateJSONError('542'));
					return $this->generateJSONError('542');
				}
			} else {
				$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['PhoneNumber'] . " " . $this->generateJSONError('540'));
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

			// Check phone number
			if (in_array("PhoneNumber", $client_key_array)) {
				$phoneNumber = $CLIENT_DATA_ARY['PhoneNumber'];

				if (strlen($phoneNumber) == 0) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['PhoneNumber'] . " " . $this->generateJSONError('522'));
					return $this->generateJSONError('522');
				} else if (!preg_match("/^[0-9\+]+$/i", stripslashes($phoneNumber))) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['PhoneNumber'] . " " . $this->generateJSONError('523'));
					return $this->generateJSONError('523');
				}
			} else  {
				$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['PhoneNumber'] . " " . $this->generateJSONError('521'));
				return $this->generateJSONError('521');
			}

			// Check mobile operator
			if (in_array("Operator", $client_key_array)) {
				$operator = $CLIENT_DATA_ARY['Operator'];

				if (strlen($operator) == 0) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['PhoneNumber'] . " " . $this->generateJSONError('539'));
					return $this->generateJSONError('539');
				}
			} else  {
				$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['PhoneNumber'] . " " . $this->generateJSONError('538'));
				return $this->generateJSONError('538');
			}

			// Check amount
			if (in_array("Amount", $client_key_array)) {
				$amount = $CLIENT_DATA_ARY['Amount'];

				if (strlen($amount) == 0) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['PhoneNumber'] . " " . $this->generateJSONError('601'));
					return $this->generateJSONError('601');
				} else if (!is_int(intval($amount)) && !is_float(intval($amount))) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['PhoneNumber'] . " " . $this->generateJSONError('602'));
					return $this->generateJSONError('602');
				}
			} else {
				return $this->generateJSONError('601');
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

				$CLIENT_DATA_ARY['Phone_Number'] = $CLIENT_DATA_ARY['PhoneNumber'];
				$CLIENT_DATA_ARY['Payment_Method'] = 1;
				$CLIENT_DATA_ARY['PlatformId'] = 1;
				$CLIENT_DATA_ARY['longitude'] = 0;
				$CLIENT_DATA_ARY['latitude'] = 0;

				// If all fields are validated correctly, call the Request Recharge API
				$REQ_SUCCESS = new RequestRechargeAPI();

				// Check that Mobile Operator is valid
				$operatorValid = $REQ_SUCCESS->checkOperator($operator);

				if ($operatorValid == 0) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['PhoneNumber'] . " " . $this->generateJSONError('554'));
					return $this->generateJSONError('554');
				}

				// Check that the user has enough available balance
				$balanceCheck = $REQ_SUCCESS->checkBalance($userId);
				$amount = $CLIENT_DATA_ARY['Amount'];

				if ($balanceCheck['balance'] < $amount) {
					$logger->error("Recharge request failed for Phone Number " . $CLIENT_DATA_ARY['PhoneNumber'] . " " . $this->generateJSONError('589'));
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
				$logger->notice("Recharge request completed for Phone Number " . $CLIENT_DATA_ARY['PhoneNumber'] . " " . json_encode($result));
				return json_encode($result);
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

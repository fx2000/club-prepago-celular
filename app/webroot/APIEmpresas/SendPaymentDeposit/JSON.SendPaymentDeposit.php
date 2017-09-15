<?php
/**
 * Send Payment notification (Bank deposit)
 *
 * Club Prepago APIEmpresas
 *
 * All taxes and discount rates for direct deposit payments are calculated by the
 * Payments controller once the payment notification is approved
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       APIEmpresas.SendPaymentDeposit
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../../API/SendPaymentDeposit/Request.SendPaymentDeposit.php";
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
		$logger->notice("Received SendPaymentDeposit request:", $CLIENT_DATA_ARY);

		$returnArray = array();
		$responseArray = array();
		$client_key_array = array();
		$check_data_array = array(
			'0' => 'UserId',
			'1' => 'Password',
			'2' => 'Amount',
			'3'	=> 'BankId',
			'4'	=> 'ReferenceNumber'
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
			$logger->error("SendPaymentDeposit request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('501'));
			return $this->generateJSONError('501');

		// Otherwise, check each parameter's validity individually
		} else {
    echo "string";
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

			// Check amount
			if (in_array("Amount", $client_key_array)) {
				$amount = $CLIENT_DATA_ARY['Amount'];

				if (strlen($amount) == 0) {
					$logger->error("SendPaymentDeposit request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('601'));
					return $this->generateJSONError('601');
				} else if (!is_int(intval($amount)) && !is_float(intval($amount))) {
					$logger->error("SendPaymentDeposit request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('602'));
					return $this->generateJSONError('602');
				}
			} else {
				$logger->error("SendPaymentDeposit request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('601'));
				return $this->generateJSONError('601');
			}

			// Check bank
			if (in_array("BankId", $client_key_array)) {
				$bankId = $CLIENT_DATA_ARY['BankId'];

				if (strlen($bankId) == 0) {
					$logger->error("SendPaymentDeposit request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('598'));
					return $this->generateJSONError('598');
				} else if (!preg_match("/^[0-9\+]+$/i", stripslashes($bankId))) {
					$logger->error("SendPaymentDeposit request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('597'));
					return $this->generateJSONError('597');
				}
			} else {
				$logger->error("SendPaymentDeposit request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('596'));
				return $this->generateJSONError('596');
			}

			// Check reference number
			if (in_array("ReferenceNumber", $client_key_array)) {
				$refNumber = $CLIENT_DATA_ARY['ReferenceNumber'];

				if (strlen($refNumber) < 0) {
					$logger->error("SendPaymentDeposit request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('599'));
					return $this->generateJSONError('599');
				}
			} else {
				$logger->error("SendPaymentDeposit request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('599'));
				return $this->generateJSONError('599');
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

				// If all fields are validated correctly, call the Send Payment Deposit API
				$REQ_SUCCESS = new RequestSendPaymentDepositAPI();

				// Check that Bank ID is valid
				$bankIdValid = $REQ_SUCCESS->checkBank($bankId);

				if ($bankIdValid == 0) {
					$logger->error("SendPaymentDeposit request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('600'));
					return $this->generateJSONError('600');
				}

				$CLIENT_DATA_ARY['amount'] = $CLIENT_DATA_ARY['Amount'];
				$CLIENT_DATA_ARY['reference_number'] = $CLIENT_DATA_ARY['ReferenceNumber'];

				// Send deposit payment notification
				$sendPaymentDeposit = $REQ_SUCCESS->sendPaymentDeposit($CLIENT_DATA_ARY);

				if ($sendPaymentDeposit != 0) {
					$status = '533';
					$obj_server_RespCode_code = new ServerStatusCode();
					$output = $obj_server_RespCode_code->getStatusCodeMessage($status);
					$arr['Status'] = '1';
					$arr['Code'] = $status;
					$arr['Message'] = $output;
					$result['Response'] = $arr;
					$logger->notice("SendPaymentDeposit request successful for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . json_encode($result));
					return json_encode($result);
				} else {
					$logger->error("SendPaymentDeposit request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('610'));
					return $this->generateJSONError('610');
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

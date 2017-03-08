<?php
/**
 * Login
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.Login
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "Request.Login.php";
include "../ServerStatusCodes.php";

class RestResponse {

	/**
	 * Generate JSON response
	 */
	function generateResponse($CLIENT_DATA_ARY) {

		// Initializing logger
		$logger = new Katzgrau\KLogger\Logger(LOG_DIR);

		// Making sure the user's password doesn't show up in the log
		$logArray = array(
			'Email' => $CLIENT_DATA_ARY['Email'],
			'DeviceId' => $CLIENT_DATA_ARY['DeviceId'],
			'PlatformId' => $CLIENT_DATA_ARY['PlatformId'],
		);

		// Logging Login
		$logger->info("============================================================");
		$logger->info("Received Login Request:", $logArray);

		$returnArray = array();
		$responseArray = array();
		$client_key_array = array();
		$check_data_array = array(
			'0' => 'Email',
			'1' => 'Password',
			'2' => 'DeviceId',
			'3' => 'PlatformId'
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
			$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('501'));
			return $this->generateJSONError('501');

		// Otherwise, check each parameter's validity individually
		} else {

			// Check email address
			if (in_array("Email", $client_key_array)) {
				$email = $CLIENT_DATA_ARY['Email'];
				$EMAIL_REG_EXP = "/^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|[A-Z]{2,4}|\d+)$/";

				if (strlen($email) == 0) {
					$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('509'));
					return $this->generateJSONError('509');
				} else if (!preg_match($EMAIL_REG_EXP, $email)) {
					$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('510'));
					return $this->generateJSONError('510');
				}
			} else {
				$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('508'));
				return $this->generateJSONError('508');
			}

			// Check password
			if (in_array("Password", $client_key_array)) {
				$password = $CLIENT_DATA_ARY['Password'];

				if (strlen($password) == 0) {
					$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('613'));
					return $this->generateJSONError('613');
				}
			} else {
				$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('612'));
				return $this->generateJSONError('612');
			}

			// Check Device ID
			if (in_array("DeviceId", $client_key_array)) {
				$deviceId = $CLIENT_DATA_ARY['DeviceId'];

				if (strlen($deviceId) == 0) {
					$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('503'));
					return $this->generateJSONError('503');
				}
			} else {
				$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('502'));
				return $this->generateJSONError('502');
			}

			// Check Platform ID
			if (in_array("PlatformId", $client_key_array)) {
				$platformId = $CLIENT_DATA_ARY['PlatformId'];

				if (strlen($platformId) == 0) {
					$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('526'));
					return $this->generateJSONError('526');
				}
			} else {
				$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('527'));
				return $this->generateJSONError('527');
			}

			// If all fields are validated correctly, call the Login API
			$REQ_SUCCESS = new RequestLoginAPI();

			// Check that Platform is valid
			$platformValid = $REQ_SUCCESS->checkPlatform($platformId);

			if ($platformValid == 0) {
				$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('527'));
				return $this->generateJSONError('527');
			}

			// Login
			$loginData = $REQ_SUCCESS->login($CLIENT_DATA_ARY);

			// Check for username/password validation
			if ($loginData == 0) {
				$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('535'));
				return $this->generateJSONError('535');

			// Check if user is active
			} else if ($loginData == 1) {
				$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('536'));
				return $this->generateJSONError('536');

			// Check if user has verified their email address
			} else if ($loginData == 2) {
				$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('537'));
				return $this->generateJSONError('537');

			// Check if user is banned
			} else if ($loginData == 3) {
				$logger->error("Login request failed for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('609'));
				return $this->generateJSONError('609');

			// Send results back
			} else {
				$status = '533';
				$obj_server_RespCode_code = new ServerStatusCode();
				$output = $obj_server_RespCode_code->getStatusCodeMessage($status);
				$version = $REQ_SUCCESS->getLatestVersion($platformId);
				$arr['Status'] = '1';
				$arr['Code'] = $status;
				$arr['Message'] = $output;
				$arr['Data'] = $loginData;
				$arr['version'] = $version;
				$result['Response'] = $arr;
				$logger->info("Login request successful for User " . $CLIENT_DATA_ARY['Email'] . " " . json_encode($result));
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

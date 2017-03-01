<?php
/**
 * Forgot Password
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.ForgotPassword
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "Request.ForgotPassword.php";
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
		$logger->notice("Received ForgotPassword request:", $CLIENT_DATA_ARY);

		$returnArray = array();
		$responseArray = array();
		$client_key_array = array();
		$check_data_array = array(
			'0' => 'Email',	
			'1' => 'DeviceId',
			'2' => 'PlatformId'
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
			$logger->error("ForgotPassword request failed for UserId " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('501'));
			return $this->generateJSONError('501');
		
		// Otherwise, check each parameter's validity individually
		} else {
			
			// Check email address
			if (in_array("Email", $client_key_array)) {
				$email = $CLIENT_DATA_ARY['Email'];
				$EMAIL_REG_EXP = "/^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|[A-Z]{2,4}|\d+)$/";
				
				if (strlen($email) == 0) {
					$logger->error("ForgotPassword request failed for UserId " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('509'));
					return $this->generateJSONError('509');
				} else if (!preg_match($EMAIL_REG_EXP, $email)) {
					$logger->error("ForgotPassword request failed for UserId " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('510'));
					return $this->generateJSONError('510');
				}
			} else {
				$logger->error("ForgotPassword request failed for UserId " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('508'));
				return $this->generateJSONError('508');
			}

			// Check Device ID
			if (in_array("DeviceId", $client_key_array)) {
				$deviceId = $CLIENT_DATA_ARY['DeviceId'];
				
				if (strlen($deviceId) == 0) {
					$logger->error("ForgotPassword request failed for UserId " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('503'));
					return $this->generateJSONError('503');
				}
			} else {
				$logger->error("ForgotPassword request failed for UserId " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('502'));
				return $this->generateJSONError('502');
			}

			// Check Platform ID
			if (in_array("PlatformId", $client_key_array)) {
				$platformId = $CLIENT_DATA_ARY['PlatformId'];
				
				if (strlen($platformId) == 0) {
					$logger->error("ForgotPassword request failed for UserId " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('526'));
					return $this->generateJSONError('526');
				}
			} else {
				$logger->error("ForgotPassword request failed for UserId " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('527'));
				return $this->generateJSONError('527');
			}

			// If all fields are validated correctly, call the forgot password API
			$REQ_SUCCESS = new RequestForgotPasswordAPI();

			// Check that Platform is valid
			$platformValid = $REQ_SUCCESS->checkPlatform($platformId);

			if ($platformValid == 0) {
				$logger->error("ForgotPassword request failed for UserId " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('527'));
				return $this->generateJSONError('527');
			}

			// Check if email is valid
			$emailValid = $REQ_SUCCESS->checkEmail($email);

			if ($emailValid == 0) {
				$logger->error("ForgotPassword request failed for UserId " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('591'));
				return $this->generateJSONError('591');
			}

			// Request forgot password
			$forgotPassword = $REQ_SUCCESS->forgotPassword($CLIENT_DATA_ARY);

			// If everything went well, return result
			if ($forgotPassword == 1) {
				$status = '592';
				$obj_server_RespCode_code = new ServerStatusCode();
				$output = $obj_server_RespCode_code->getStatusCodeMessage($status);
				$arr['Status'] = '1';
				$arr['Code'] = $status;
				$arr['Message'] = $output;
				$result['Response'] = $arr;
				$logger->notice("ForgotPassword request successful for UserId " . $CLIENT_DATA_ARY['Email'] . " " . json_encode($result));
				return json_encode($result);

			// Otherwise, return appropriate error code
			} else {
				$logger->error("ForgotPassword request failed for UserId " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('593'));
				return $this->generateJSONError('593');
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

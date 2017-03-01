<?php
/**
 * Update Profile
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.UpdateProfile
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "Request.UpdateProfile.php";
include "../ServerStatusCodes.php";

class RestResponse {

	/**
	 * Generate JSON response
	 */
	function generateResponse($CLIENT_DATA_ARY) {

		// Initializing logger
		$logger = new Katzgrau\KLogger\Logger(LOG_DIR);

		// Logging Forgot Password
		$logger->info("============================================================");
		$logger->info("Received UpdateProfile request:", $CLIENT_DATA_ARY);
	
		$returnArray = array();
		$responseArray = array();
		$client_key_array = array();
		$check_data_array = array(
			'0' => 'Email',
			'1' => 'Phone_Number',	
			'2' => 'Address',
			'3' => 'City',
			'4' => 'Province',
			'5' => 'UserId',
			'6' => 'DeviceId',
			'7' => 'PlatformId',
		);

		// Check if the correct parameters are being sent and mark as (S)uccess or (F)ailed
		foreach ($CLIENT_DATA_ARY as $key => $val) {
			array_push($client_key_array, $key);
		}
		
		for ($i = 0; $i < count($client_key_array); $i++) {
			
			if (in_array($client_key_array[$i],$check_data_array)) {
				array_push($returnArray, 'S');
			} else {
				array_push($returnArray, 'F');
			}
		}

		// If parameter check fails, send an error message
		if (in_array("F", $returnArray)) {
			$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('501'));
			return $this->generateJSONError('501');

		// Otherwise, check each parameter's validity individually
		} else {

			// Check address
			if (in_array("Address", $client_key_array)) {
				$address = $CLIENT_DATA_ARY['Address'];
				
				if (strlen($address) == 0) {
					$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('518'));
					return $this->generateJSONError('518');
				}
			} else {
				$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('517'));
				return $this->generateJSONError('517');
			}

			// Check phone number
			if (in_array("Phone_Number", $client_key_array)) {
				$phoneNumber = $CLIENT_DATA_ARY['Phone_Number'];
				
				if (strlen($phoneNumber) == 0) {
					$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('522'));
					return $this->generateJSONError('522');
				} else if (!preg_match("/^[0-9\+]+$/i", stripslashes($phoneNumber))) {
					$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('523'));
					return $this->generateJSONError('523');
				}
			} else  {
				$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('521'));
				return $this->generateJSONError('521');
			}

			// Check email address
			if (in_array("Email", $client_key_array)) {
				$email = $CLIENT_DATA_ARY['Email'];
				$EMAIL_REG_EXP = "/^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|[A-Z]{2,4}|\d+)$/";
				
				if (strlen($email) == 0) {
					$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('509'));
					return $this->generateJSONError('509');
				} else if (!preg_match($EMAIL_REG_EXP, $email)) {
					$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('510'));
					return $this->generateJSONError('510');
				}
			} else {
				$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('508'));
				return $this->generateJSONError('508');
			}

			// Check User ID
			if (in_array("UserId", $client_key_array)) {
				$userId = $CLIENT_DATA_ARY['UserId'];
				
				if (strlen($userId) == 0) {
					$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('541'));
					return $this->generateJSONError('541');
				} else if (!preg_match("/^[0-9\+]+$/i", stripslashes($userId))) {
					$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('542'));
					return $this->generateJSONError('542');
				}
			} else {
				$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('540'));
				return $this->generateJSONError('540');
			}

			// Check Device ID
			if (in_array("DeviceId", $client_key_array)) {
				$deviceId = $CLIENT_DATA_ARY['DeviceId'];
				
				if (strlen($deviceId) == 0) {
					$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('503'));
					return $this->generateJSONError('503');
				}
			} else {
				$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('502'));
				return $this->generateJSONError('502');
			}

			// Check Platform ID
			if (in_array("PlatformId", $client_key_array)) {
				$platformId = $CLIENT_DATA_ARY['PlatformId'];
				
				if (strlen($platformId) == 0) {
					$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('526'));
					return $this->generateJSONError('526');
				}
			} else {
				$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('527'));
				return $this->generateJSONError('527');
			}
			
			// Check city
			if (in_array("City", $client_key_array)) {
				$city = $CLIENT_DATA_ARY['City'];
				
				if (strlen($city) == 0) {
					$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('713'));
					return $this->generateJSONError('713');
				}
			} else {
				$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('712'));
				return $this->generateJSONError('712');
			}
			
			// Check state or province
			if (in_array("Province", $client_key_array)) {
				$province = $CLIENT_DATA_ARY['Province'];
				
				if (strlen($province) == 0) {
					$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('715'));
					return $this->generateJSONError('715');
				}
			} else {
				$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('714'));
				return $this->generateJSONError('714');
			}

			$REQ_SUCCESS = new RequestUpdateProfileAPI();

			// Check that Platform is valid
			$platformValid = $REQ_SUCCESS->checkPlatform($platformId);

			if ($platformValid == 0) {
				$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('527'));
				return $this->generateJSONError('527');
			}

			// Check that User ID is valid
			$userIdValid = $REQ_SUCCESS->checkUser($userId);

			if ($userIdValid == 0) {
				$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('543'));
				return $this->generateJSONError('543');
			}

			// Check that Device ID is valid
			$deviceIdValid = $REQ_SUCCESS->checkDevice($deviceId, $platformId, $userId);

			if ($deviceIdValid == 0) {
				$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('544'));
				return $this->generateJSONError('544');
			}

			// Check if email exists
			$emailValid = $REQ_SUCCESS->checkEmail($email, $userId);

			if ($emailValid > 0) {
				$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('529'));
				return $this->generateJSONError('529');
			}
			
			// Request profile update
			$update = $REQ_SUCCESS->updateProfile($CLIENT_DATA_ARY);

			if ($update != 0) {
				$status = '533';
				$obj_server_RespCode_code = new ServerStatusCode();
				$output = $obj_server_RespCode_code->getStatusCodeMessage($status);
				$arr['Status'] = '1';
				$arr['Code'] = $status;
				$arr['Message'] = $output;
				$result['Response'] = $arr;
				$logger->info("UpdateProfile request successful for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . json_encode($result));
				return json_encode($result);
			} else {
				$logger->error("UpdateProfile request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('555'));
				return $this->generateJSONError('555');
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

<?php
/**
 * Register
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.Register
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "Request.Register.php";
include "../../APIConfig/ServerStatusCodes.php";

class RestResponse {

	/**
	 * Generate JSON response
	 */
	function generateResponse($CLIENT_DATA_ARY) {

		// Initializing logger
		$logger = new Katzgrau\KLogger\Logger(LOG_DIR);

		// Making sure the user's password doesn't show up in the log
		$logArray = array(
			'Name'         => $CLIENT_DATA_ARY['Name'],
			'TaxId'        => $CLIENT_DATA_ARY['TaxId'],
			'Email'        => $CLIENT_DATA_ARY['Email'],
			'Address'      => $CLIENT_DATA_ARY['Address'],
			'City'         => $CLIENT_DATA_ARY['City'],
			'Province'     => $CLIENT_DATA_ARY['Province'],
			'Country'      => $CLIENT_DATA_ARY['Country'],
			'Phone_Number' => $CLIENT_DATA_ARY['Phone_Number'],
			'SponsorId'    => $CLIENT_DATA_ARY['SponsorId'],
			'DeviceId'     => $CLIENT_DATA_ARY['DeviceId'],
			'PlatformId'   => $CLIENT_DATA_ARY['PlatformId'],
			'latitude'     => $CLIENT_DATA_ARY['latitude'],
			'longitude'    => $CLIENT_DATA_ARY['longitude']
		);

		// Logging Forgot Password
		$logger->notice("============================================================");
		$logger->notice("Received Register request:", $logArray);

		$returnArray = array();
		$responseArray = array();
		$client_key_array = array();
		$check_data_array = array(
			'0' => 'Name',
			'1' => 'TaxId',
			'2' => 'Email',
			'3' => 'Password',
			'4' => 'Address',
			'5' => 'City',
			'6' => 'Province',
			'7' => 'Country',
			'8' => 'Phone_Number',
			'9' => 'SponsorId',
			'10' => 'DeviceId',
			'11' => 'PlatformId',
			'12' => 'latitude',
			'13' => 'longitude'
		);

		// Check if the correct parameters are being sent and mark as (S)uccess or (F)ailed
		foreach ($CLIENT_DATA_ARY as $key => $val) {
			array_push($client_key_array, $key);
		}

		for ($i = 0; $i < (count($client_key_array) - 1); $i++) {

			if (in_array($client_key_array[$i], $check_data_array)) {
				array_push($returnArray, 'S');
			} else {
				array_push($returnArray, 'F');
			}
		}

		// If parameter check fails, send an error message
		if (in_array("F", $returnArray)) {
			$logger->error("Register request failed for user " . $this->generateJSONError('501'));
			return $this->generateJSONError('501');

		// Otherwise, check each parameter's validity individually
		} else {

			// Check Name
			if (in_array("Name", $client_key_array)) {
				$fname = $CLIENT_DATA_ARY['Name'];

				if (strlen($fname) == 0) {
					$logger->error("Register request failed for user " . $this->generateJSONError('505'));
					return $this->generateJSONError('505');
				}
			} else  {
				$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " . $this->generateJSONError('504'));
				return $this->generateJSONError('504');
			}

			// Check email address
			if (in_array("Email", $client_key_array)) {
				$email = $CLIENT_DATA_ARY['Email'];
				$EMAIL_REG_EXP = "/^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|[A-Z]{2,4}|\d+)$/";

				if (strlen($email) == 0) {
					$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " .
						$this->generateJSONError('509'));
					return $this->generateJSONError('509');
				} else if (!preg_match($EMAIL_REG_EXP, $email)) {
					$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " .
						$this->generateJSONError('510'));
					return $this->generateJSONError('510');
				}
			} else {
				$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " .
					$this->generateJSONError('508'));
				return $this->generateJSONError('508');
			}

			// Check password
			if (in_array("Password", $client_key_array)) {
				$password = $CLIENT_DATA_ARY['Password'];

				if (strlen($password) == 0) {
					$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " .
						$this->generateJSONError('515'));
					return $this->generateJSONError('515');
				} else if (strlen($password) < 6) {
					$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Fisrt_Name'] . " " .
						$this->generateJSONError('516'));
					return $this->generateJSONError('516');
				}
			} else {
				$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " .
					$this->generateJSONError('514'));
				return $this->generateJSONError('514');
			}

			// Check Country
			if (in_array("Country", $client_key_array)) {
				$country = $CLIENT_DATA_ARY['Country'];

				if (strlen($country) == 0) {
					$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " .
						$this->generateJSONError('614'));
					return $this->generateJSONError('614');
				}
			} else {
				$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " .
					$this->generateJSONError('614'));
				return $this->generateJSONError('614');
			}

			// Check phone number
			if (in_array("Phone_Number", $client_key_array)) {
				$phoneNumber = $CLIENT_DATA_ARY['Phone_Number'];

				if (strlen($phoneNumber) == 0) {
					$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " .
						$this->generateJSONError('522'));
					return $this->generateJSONError('522');
				} else if (!preg_match("/^[0-9\+]+$/i", stripslashes($phoneNumber))) {
					$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " .
						$this->generateJSONError('523'));
					return $this->generateJSONError('523');
				}
			} else  {
				$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " .
					$this->generateJSONError('521'));
				return $this->generateJSONError('521');
			}

			// Check sponsor
			if (in_array("SponsorId", $client_key_array)) {
				$sponsorId = $CLIENT_DATA_ARY['SponsorId'];
				$sponsorId = trim($sponsorId,'0');
			}

			// Check Device ID
			if (in_array("DeviceId", $client_key_array)) {
				$deviceId = $CLIENT_DATA_ARY['DeviceId'];

				if (strlen($deviceId) == 0) {
					$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " .
						$this->generateJSONError('503'));
					return $this->generateJSONError('503');
				}
			} else {
				$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " .
					$this->generateJSONError('502'));
				return $this->generateJSONError('502');
			}

			// Check Platform ID
			if (in_array("PlatformId", $client_key_array)) {
				$platformId = $CLIENT_DATA_ARY['PlatformId'];

				if (strlen($platformId) == 0) {
					$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " .
						$this->generateJSONError('526'));
					return $this->generateJSONError('526');
				}
			} else {
				$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " .
					$this->generateJSONError('527'));
				return $this->generateJSONError('527');
			}

			// If all fields are validated correctly, call the Request Register API
			$REQ_SUCCESS = new RequestRegisterAPI();

			// Check that Platform is valid
			$platformValid = $REQ_SUCCESS->checkPlatform($platformId);

			if ($platformValid == 0) {
				$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " .
					$this->generateJSONError('527'));
				return $this->generateJSONError('527');
			}

			// Check if email exists
			$emailValid = $REQ_SUCCESS->checkEmail($email);

			if ($emailValid > 0) {
				$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " .
					$this->generateJSONError('529'));
				return $this->generateJSONError('529');
			}

			// Request registration
			$register = $REQ_SUCCESS->register($CLIENT_DATA_ARY);

			if ($register != 0) {
				$status = '533';
				$obj_server_RespCode_code = new ServerStatusCode();
				$output = $obj_server_RespCode_code->getStatusCodeMessage($status);
				$arr['Status'] = '1';
				$arr['Code'] = $status;
				$arr['Message'] = $output;
				$arr['data'] = $register;
				$result['Response'] = $arr;
				$logger->notice("Register request successful for user " . $CLIENT_DATA_ARY['Name'] . " " .
					json_encode($result));
				return json_encode($result);
			} else {
				$logger->error("Register request failed for user " . $CLIENT_DATA_ARY['Name'] . " " .
					$this->generateJSONError('534'));
				return $this->generateJSONError('534');
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

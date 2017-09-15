<?php
/**
 * Get Settings
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.GetSettings
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "Request.GetSettings.php";
include "../../APIConfig/ServerStatusCodes.php";

class RestResponse {

	/**
	 * Generate JSON response
	 */
	function generateResponse($CLIENT_DATA_ARY) {

		// Initializing logger
		$logger = new Katzgrau\KLogger\Logger(LOG_DIR);

		// Logging Forgot Password
		$logger->info("============================================================");
		$logger->info("Received GetSettings request:", $CLIENT_DATA_ARY);

		$returnArray = array();
		$responseArray = array();
		$client_key_array = array();
		$check_data_array = array(
			'0' => 'DeviceId',
			'1' => 'PlatformId'
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
			$logger->error("GetSettings request failed " . $this->generateJSONError('501'));
			return $this->generateJSONError('501');
		} else {

			// Check Device ID
			if (in_array("DeviceId", $client_key_array)) {
				$deviceId = $CLIENT_DATA_ARY['DeviceId'];

				if (strlen($deviceId) == 0) {
					$logger->error("GetSettings request failed " . $this->generateJSONError('503'));
					return $this->generateJSONError('503');
				}
			} else {
				$logger->error("GetSettings request failed " . $this->generateJSONError('502'));
				return $this->generateJSONError('502');
			}

			// Check Platform ID
			if (in_array("PlatformId", $client_key_array)) {
				$platformId = $CLIENT_DATA_ARY['PlatformId'];

				if (strlen($platformId) == 0) {
					$logger->error("GetSettings request failed " . $this->generateJSONError('526'));
					return $this->generateJSONError('526');
				}
			} else {
				$logger->error("GetSettings request failed " . $this->generateJSONError('527'));
				return $this->generateJSONError('527');
			}

			// If all fields are validated correctly, call the Get Settings API
			$REQ_SUCCESS = new RequestGetSettingsAPI();

			// Check that Platform is valid
			$platformValid = $REQ_SUCCESS->checkPlatform($platformId);

			if ($platformValid == 0) {
				$logger->error("GetSettings request failed " . $this->generateJSONError('527'));
				return $this->generateJSONError('527');
			}

			// Request Settings
			$Settings = $REQ_SUCCESS->getSettings($CLIENT_DATA_ARY);


			if ($Settings > 0) {
				$status = '533';
				$obj_server_RespCode_code = new ServerStatusCode();
				$output = $obj_server_RespCode_code->getStatusCodeMessage($status);
				$arr['Status'] = '1';
				$arr['Code'] = $status;
				$arr['Message'] = $output;
				$arr['Data'] = $Settings;
				$result['Response'] = $arr;
				$logger->info("GetSettings request successful " . json_encode($result));
				return json_encode($result);
			} else {
				$logger->error("GetSettings request failed " . $this->generateJSONError('588'));
				return $this->generateJSONError('588');
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

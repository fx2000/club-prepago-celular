<?php
/**
 * Update Favorite
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.UpdateFavorite
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "Request.UpdateFavorite.php";
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
		$logger->info("Received UpdateFavorite request:", $CLIENT_DATA_ARY);

		$returnArray = array();
		$responseArray = array();
		$client_key_array = array();
		$check_data_array = array(
			'0' => 'FavoriteId',
			'1' => 'Name',
			'2' => 'Phone_Number',
			'3' => 'Operator',
			'4' => 'UserId',
			'5' => 'DeviceId',
			'6' => 'PlatformId'
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
			$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('501'));
			return $this->generateJSONError('501');

		// Otherwise, check each parameter's validity individually
		} else {

			// Check Favorite ID
			if (in_array("FavoriteId",$client_key_array)) {
				$favoriteId = $CLIENT_DATA_ARY['FavoriteId'];

				if (strlen($favoriteId) == 0) {
					$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('580'));
					return $this->generateJSONError('580');
				}
			} else {
				$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('579'));
				return $this->generateJSONError('579');
			}

			// Check favorite's name
			if (in_array("Name", $client_key_array)) {
				$name = $CLIENT_DATA_ARY['Name'];

				if (strlen($name) == 0) {
					$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('505'));
					return $this->generateJSONError('505');
				}
			} else  {
				$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('504'));
				return $this->generateJSONError('504');
			}

			// Check favorite's phone number
			if (in_array("Phone_Number", $client_key_array)) {
				$phoneNumber = $CLIENT_DATA_ARY['Phone_Number'];

				if (strlen($phoneNumber) == 0) {
					$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('522'));
					return $this->generateJSONError('522');
				} else if (!preg_match("/^[0-9\+]+$/i", stripslashes($phoneNumber))) {
					$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('523'));
					return $this->generateJSONError('523');
				}
			} else  {
				$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('521'));
				return $this->generateJSONError('521');
			}

			// Check favorite's mobile operator
			if (in_array("Operator", $client_key_array)) {
				$operator = $CLIENT_DATA_ARY['Operator'];

				if (strlen($operator) == 0) {
					$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('539'));
					return $this->generateJSONError('539');
				}
			} else  {
				$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('538'));
				return $this->generateJSONError('538');
			}

			// Check User ID
			if (in_array("UserId", $client_key_array)) {
				$userId = $CLIENT_DATA_ARY['UserId'];

				if (strlen($userId) == 0) {
					$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('541'));
					return $this->generateJSONError('541');
				} else if (!preg_match("/^[0-9\+]+$/i", stripslashes($userId))) {
					$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('542'));
					return $this->generateJSONError('542');
				}
			} else {
				$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('540'));
				return $this->generateJSONError('540');
			}

			// Check Device ID
			if (in_array("DeviceId", $client_key_array)) {
				$deviceId = $CLIENT_DATA_ARY['DeviceId'];

				if (strlen($deviceId) == 0) {
					$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('503'));
					return $this->generateJSONError('503');
				}
			} else {
				$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('502'));
				return $this->generateJSONError('502');
			}

			// Check Platform ID
			if (in_array("PlatformId", $client_key_array)) {
				$platformId = $CLIENT_DATA_ARY['PlatformId'];

				if (strlen($platformId) == 0) {
					$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('526'));
					return $this->generateJSONError('526');
				}
			} else {
				$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('527'));
				return $this->generateJSONError('527');
			}

			// If all fields are validated correctly, call the Update Favorite API
			$REQ_SUCCESS = new RequestUpdateFavoriteAPI();

			// Check that Platform is valid
			$platformValid = $REQ_SUCCESS->checkPlatform($platformId);

			if ($platformValid == 0) {
				$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('527'));
				return $this->generateJSONError('527');
			}

			// Check that User ID is valid
			$userIdValid = $REQ_SUCCESS->checkUser($userId);

			if ($userIdValid == 0) {
				$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('543'));
				return $this->generateJSONError('543');
			}

			// Check that Device ID is valid
			$deviceIdValid = $REQ_SUCCESS->checkDevice($deviceId, $platformId, $userId);

			if ($deviceIdValid == 0) {
				$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('544'));
				return $this->generateJSONError('544');
			}

			// Check that Mobile Operator is valid
			$operatorValid = $REQ_SUCCESS->checkOperator($operator);

			if ($operatorValid == 0) {
				$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('554'));
				return $this->generateJSONError('554');
			}

			// Check if phone number already exists in favorites
			$phoneNumberValid = $REQ_SUCCESS->checkPhoneNumber($phoneNumber, $userId, $favoriteId);

			if ($phoneNumberValid > 0) {
				$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('545'));
				return $this->generateJSONError('545');
			}

			// Check if Favorite exists
			$favoriteIdValid = $REQ_SUCCESS->checkFavoriteId($favoriteId);

			if ($favoriteIdValid = 0) {
				$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('579'));
				return $this->generateJSONError('579');
			}

			// Request new Favorite update
			$upFavorite = $REQ_SUCCESS->updateFavorite($CLIENT_DATA_ARY);

			if ($upFavorite != 0) {
				$status = '533';
				$obj_server_RespCode_code = new ServerStatusCode();
				$output = $obj_server_RespCode_code->getStatusCodeMessage($status);
				$arr['Status'] = '1';
				$arr['Code'] = $status;
				$arr['Message'] = $output;
				$result['Response'] = $arr;
				$logger->info("UpdateFavorite request successful for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . json_encode($result));
				return json_encode($result);
			} else {
				$logger->error("UpdateFavorite request failed for UserId " . $CLIENT_DATA_ARY['UserId'] . " " . $this->generateJSONError('546'));
				return $this->generateJSONError('546');
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

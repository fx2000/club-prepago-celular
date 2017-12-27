<?php
/**
 * Get User for transfer USD to BSF
 *
 * Club Prepago API
 *
 * @copyright     Club Prepago Celular(tm) Project
 * @package       API.GetUserUsdBsf
 * @since         Club Prepago Celular(tm) v 1.1.0
 */
include "Request.GetUserUsdBsf.php";
include "../../APIConfig/ServerStatusCodes.php";

class RestResponse {

	/**
	 * Generate JSON response
	 */
	function generateResponse($CLIENT_DATA_ARY) {

		// Initializing logger
		$logger = new Katzgrau\KLogger\Logger(LOG_DIR);

		// Logging
		$logger->info("============================================================");
		$logger->info("Received GetUserUsdBsf request:", $CLIENT_DATA_ARY);

		$returnArray = array();
		$responseArray = array();
		$client_key_array = array();
		$check_data_array = array(
			'0' => 'Email',
      '1' => 'Amount'
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
			$logger->error("GetUserUsdBsf request failed for " . $this->generateJSONError('501'));
			return $this->generateJSONError('501');
		} else {

      // Check email address
			if (in_array("Email", $client_key_array)) {
				$email = $CLIENT_DATA_ARY['Email'];
				$EMAIL_REG_EXP = "/^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|[A-Z]{2,4}|\d+)$/";

				if (strlen($email) == 0) {
					$logger->error("GetUserUsdBsf for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('509'));
					return $this->generateJSONError('509');
				} else if (!preg_match($EMAIL_REG_EXP, $email)) {
					$logger->error("GetUserUsdBsf for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('510'));
					return $this->generateJSONError('510');
				}
			} else {
				$logger->error("GetUserUsdBsf for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('508'));
				return $this->generateJSONError('508');
			}

      // Check amount
			if (in_array("Amount", $client_key_array)) {
				$amount = $CLIENT_DATA_ARY['Amount'];

				if (strlen($amount) == 0) {
					$logger->error("GetUserUsdBsf for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('601'));
					return $this->generateJSONError('601');
				} else if (!is_int(intval($amount)) && !is_float(intval($amount))) {
					$logger->error("GetUserUsdBsf for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('602'));
					return $this->generateJSONError('602');
				}
			} else {
				return $this->generateJSONError('601');
			}

			// If all fields are validated correctly, call the Get User Info API
			$REQ_SUCCESS = new RequestGetUserUsdBsfAPI();

			// Request profile
			$userInfo = $REQ_SUCCESS->getUserInfo($CLIENT_DATA_ARY);

			// If everything went well, return result
			if ($userInfo > 0) {
				$status = '533';
				$obj_server_RespCode_code = new ServerStatusCode();
				$output = $obj_server_RespCode_code->getStatusCodeMessage($status);
				$arr['Status'] = '1';
				$arr['Code'] = $status;
				$arr['Message'] = $output;
				$arr['Data'] = $userInfo;
				$result['Response'] = $arr;
				$logger->info("GetUserUsdBsf request successful for User " . $CLIENT_DATA_ARY['Email'] . " " . json_encode($result));
				return json_encode($result);
			}

			// Otherwise, return appropriate error code
			else {
				$logger->error("GetUserUsdBsf request failed for User " . $CLIENT_DATA_ARY['Email'] . " " . $this->generateJSONError('611'));
				return $this->generateJSONError('611');
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

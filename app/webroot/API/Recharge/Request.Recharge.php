<?php
/**
 * Recharge
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.Recharge
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../Dbconn.php";

class RequestRechargeAPI extends Dbconn {

	/**
	 * Generate a new Recharge
	 */
	function recharge($data) {

		// Declare the results array
		$resultArr = array();

		// Make sure amount is rounded to 2 decimal spaces
		$amount = round($data['Amount'], 2);

		// Get current date & time
		$date = date('Y-m-d H:i:s');

		// Set tax amount to zero for resellers using prepaid balance
		$tax = 0;

		// Set total amount
		$totalAmount = $amount + $tax;

		// Check user activation and ban status
		$arrUserStatus = $this->checkStatus($data['UserId']);
		$arrUserBanned = $this->checkBan($data['UserId']);

		// If user is active and not banned, proceed normally
		if ($arrUserStatus['status'] == ACTIVE && $arrUserBanned['banned'] == NOT_BANNED) {

			// Insert preliminary recharge information into recharges table
			$insRecharge =
				"INSERT INTO recharges (
					user_id,
					user_type,
					phone_number,
					operator,
					amount,
					tax_amount,
					total_amount,
					payment_method,
					recharge_date,
					x,
					y
				) VALUES (" .
					$data['UserId'] . "," .
					$data['PlatformId'] . "," . // Change this to check the user tables
					"\"" . $data['Phone_Number'] . "\"" . "," .
					$data['Operator'] . "," .
					$amount . "," .
					$tax . "," .
					$totalAmount . "," .
					$data['Payment_Method'] . "," .
					"\"" . $date . "\"" . "," . 
					"\"" . $data['longitude'] . "\"" . "," .
					"\"" . $data['latitude'] . "\"" .
				")";
			$resInsRecharge = $this->fireQuery($insRecharge);

			// Generate merchant_txn_id for TrxEngine
			//$rechargeId = mysql_insert_id();
			$rechargeId = mysqli_insert_id($this->_conn);
			$merchantTxnId = str_pad($rechargeId, 10, "0", STR_PAD_LEFT);

			// Generate promo number for lottery promotions
			$prePromo = rand(0,9999);
			$promo = str_pad($prePromo, 4, "0", STR_PAD_LEFT);

			// Calculate points the user will earn if the recharge is successful
			$selSetting =
				"SELECT *
					FROM settings";
			$resSetting = $this->fireQuery($selSetting);
			$arrSetting = $this->fetchAssoc($resSetting);

			// Making sure points are awarded for each whole dollar, no fractions
			$points = $arrSetting['reward_recharge'] * floor($amount);

			// Excecute recharge
			$rechargeStatus = $this->doRecharge(
				$data['Phone_Number'], $data['Operator'], $amount, $merchantTxnId, $data['UserId'], $data['PlatformId']
			);

			// Read TrxEngine's response
			$arrRechargeStatus = explode(':', $rechargeStatus);
			$status = $arrRechargeStatus[1];
			$txnId = '';

			// If Recharge is successful
			if ($status == '00') {
				$rechargeDone = 1;
				$message = 'Recharge has been successful';
				$messageCode = '563';

				// Adjust the user's balance
				$updUserBal = $this->fireQuery(
					"UPDATE users
						SET balance = balance - " . $amount . " WHERE id = " . $data['UserId']
				);

				// If not a reseller, adjust the user's points
				if ($data['PlatformId'] == 1) {
					$updUserPoints = $this->fireQuery(
						"UPDATE users
							SET points = points + " . $points . " WHERE id = ". $data['UserId']
					);
				}

				// Adjust Mobile Operator balance
				$updOperatorBal = $this->fireQuery(
					"UPDATE operators
						SET balance = balance - " . $amount . " WHERE id = " . $data['Operator']
				);

				// Check if the balance dropped below the warning levels
				$this->lowInventoryEmail($data['Operator']);

			// If Recharge failed, generate error message
			} else {
				$rechargeDone = 0;

				switch ($status) {
					case 1:
						$messageCode = '564';
						$message = 'Improper MerchantID';
						break;
					case 2:
						$messageCode = '565';
						$message = 'Improper Customer PhoneNo';
						break;
					case 3:
						$messageCode = '566';
						$message = 'Improper MerchantPIN';
						break;
					case 4:
						$messageCode = '567';
						$message = 'The minimum recharge amount is ' . $arrRechargeStatus[2];
						break;
					case 5:
						$messageCode = '568';
						$message = 'The maximum recharge amount is ' . $arrRechargeStatus[2];
						break;
					case 6:
						$messageCode = '569';
						$message = 'Operation not supported or data inconsistency';
						break;
					case 7:
						$messageCode = '570';
						$message = 'Remote system unavailable';
						break;
					case 8:
						$messageCode = '571';
						$message = 'Insufficient funds';
						break;
					case 9:
						$messageCode = '572';
						$message = 'Duplicate Transaction';
						break;
					case 10:
						$messageCode = '573';
						$message = 'Missing MerchantID, CustomerPhoneNo, MerchantPIN, TopupAmt';
						break;
					case 11:
						$messageCode = '574';
						$message = 'Improper ProductID';
						break;
					case 12:
						$messageCode = '575';
						$message = 'Merchant account has been disabled';
						break;
					case 13:
						$messageCode = '576';
						$message = 'Improper Terminal';
						break;
					default:
						$messageCode = '577';
						$message = 'Something went wrong';
						break;
				}
			}

			// Insert final Recharge information into recharges table
			$updRecharge =
				"UPDATE recharges
					SET status = " . $rechargeDone . "," .
						" promo_number = " . "\"" . $promo . "\"" ."," .
						" merchant_txn_id = " . "\"" . $merchantTxnId . "\"" . "," .
						" response_code = " . "\"" . $status . "\"" . "," .
						" response_message = " . "\"" . $message . "\"" . "," .
						" points = " . $points .
						" WHERE id = " . $rechargeId;
			$resUpdRecharge = $this->fireQuery($updRecharge);

			// Add final recharge status to results array
			$userDetail = $this->fireQuery(
				"SELECT *
					FROM users
					WHERE id = " . $data['UserId']
			);
			$arrUser = $this->fetchAssoc($userDetail);
			$resultArr['Status'] = $rechargeDone;
			$resultArr['Code'] = $messageCode;
			$resultArr['Data']['tax'] = $tax;
			$resultArr['Data']['totalAmount'] = $totalAmount;
			$resultArr['Data']['ReferenceNo'] = $merchantTxnId;
			$resultArr['Data']['Date'] = date('Y-m-d H:i:s');
			$resultArr['Data']['RewardPoint'] = $points;

			// If recharge is succesful, put updated user's balance in the results array
			if ($rechargeDone == 1) {
				$resultArr['Data']['AvailableBalance'] = $arrUser['balance'];
			}

			// Return recharge information
			return $resultArr;

		// If user is inactive, return with an error code
		} else if ($arrUserStatus['status'] == INACTIVE && $arrUserBanned['banned'] == NOT_BANNED) {
			$resultArr['Status'] = 0;
			$resultArr['Code'] = 536;
			$resultArr['Data']['tax'] = $tax;
			$resultArr['Data']['totalAmount'] = $totalAmount;
			$resultArr['Data']['Date'] = date('Y-m-d H:i:s');
			return $resultArr;

		// If user is banned, return with an error code
		} else if ($arrUserBanned['banned'] == BANNED) {
			$resultArr['Status'] = 0;
			$resultArr['Code'] = 609;
			$resultArr['Data']['tax'] = $tax;
			$resultArr['Data']['totalAmount'] = $totalAmount;
			$resultArr['Data']['Date'] = date('Y-m-d H:i:s');
			return $resultArr;
		}
	}

	/**
	 * Send a new Recharge to trxengine
	 */
	function doRecharge($phoneNumber, $operatorId, $amount, $merchantTxnId, $userId, $platformId) {

		// Get trxengine settings for the mobile operator
		$selproduct =
			"SELECT *
				FROM operator_credentials
				WHERE operator_id = ". $operatorId;
		$resproduct = $this->fireQuery($selproduct);
		$arrproduct = $this->fetchAssoc($resproduct);

		// Assign appropriate values
		$url = $arrproduct['ip_address'];
		$port = $arrproduct['port'];
		$merchantId = $arrproduct['username'];
		$merchantPin = $arrproduct['password'];
		$productId = $arrproduct['product_id'];

		// Generate TrxEngine XML file
		$header[] = "Host:" . $url . ":" . $port;
		$header[] = "Content-type: text/xml";
		$NewXml =
			'<methodCall>
			<methodName>roms.esinglextrapr</methodName>
			<params>
			<param><value>' . $merchantId .'</value></param>
			<param><value>' . $merchantPin .'</value></param>
			<param><value>' . $phoneNumber .'</value></param>
			<param><value>' . number_format((float)$amount, 2, '.', '') .'</value></param>
			<param><value>' . $merchantTxnId .'</value></param>
			<param><value>' . $platformId . '</value></param>
			<param><value>' . $userId . '</value></param>
			<param><value>' . date('YmdHis') . '</value></param>
			<param><value>' . $productId . '</value></param>
			</params>
			</methodCall>';
		$ch = curl_init();

		// Check Mobile Operator and set target server
		curl_setopt($ch, CURLOPT_URL, $url . ":" . $port);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );

		// Add headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		// Set POST
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

		// Add XML content
		curl_setopt($ch, CURLOPT_POSTFIELDS, $NewXml);

		// Send transaction to trxengine
		$result = curl_exec($ch);

		// Close connection
		curl_close($ch);

		// Read and return results
		$result1 = simplexml_load_string($result);
		$result2 = $result1->params->param->value;
		$result3 = $result2->string;
		return $result3;
	}

	/**
	 * Check for Inventory Warnings, and send low inventory reminders
	 */
	function lowInventoryEmail($operator) {

		// Check operators table for inventory warning levels
		$selMinLimit =
			"SELECT *
				FROM operators
				where id = " . $operator;
		$resMinLimit = $this->fireQuery($selMinLimit);
		$arrMinLimit = $this->fetchAssoc($resMinLimit);

		// Check if new balance is below the limit
		if ($arrMinLimit['balance'] <= $arrMinLimit['minimum_limit']) {

			// Generate low inventory warning email
			$mail = new PHPMailer(true);

			// Set PHP Mailer parameters
			$mail->isSMTP();
			$mail->Host = EMAIL_SERVER;
			$mail->Port = 465;
			$mail->Timeout = 30;
			$mail->SMTPSecure = 'ssl';
			$mail->SMTPAuth = true;
			$mail->Username = EMAIL_USER;
			$mail->Password = EMAIL_PASSWORD;
			$mail->From = EMAIL_FROM;
			$mail->FromName = EMAIL_SENDER_NAME;
			$mail->addAddress(EMAIL_STAFF);
			$mail->WordWrap = 50;
			$mail->isHTML(true);
			$mail->CharSet = "UTF-8";
			$mail->Subject = 'Inventario bajo para '.$arrMinLimit['name'];
			$mail->Body =
				'<html>
				<body>
					<div style="font-family:Tahoma;">
						El inventario para recargas de ' . $arrMinLimit['name'] . ' ha caído por debajo de su límite mínimo.
						El balance actual es de B/. ' . $arrMinLimit['balance'] . '
					</div>
				</body>
				<html>';

			if (!$mail->send()) {
				return 0;
			} else {
				return 1;
			}
		}
	}

	/**
	 * Check User ID
	 */
	function checkUser($userId) {
		$query =
			"SELECT id
				FROM users
				WHERE id = " . $userId;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
		return $value;
	}

	/**
	 * Check Device ID
	 */
	function checkDevice($deviceId, $platformId, $userId) {
		$query =
			"SELECT id
				FROM devices
				WHERE device_id = " . $deviceId . " AND user_id = " . $userId . " AND platform_id = " . $platformId . " AND login_status = " . SIGNED_IN;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
		return $value;
	}

	/**
	 * Check Platform ID
	 */
	function checkPlatform($platformId) {
		$query =
			"SELECT id
				FROM platforms
				WHERE id = " . $platformId;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
		return $value;
	}

	/**
	 * Check a user's status
	 */
	function checkStatus($userId) {
		$query =
			"SELECT status
				FROM users
				WHERE id = " . $userId;
		$result = $this->fireQuery($query);
		$value = $this->fetchAssoc($result);
		return $value;
	}

	/**
	 * Check a user's ban status
	 */
	function checkBan($userId) {
		$query =
			"SELECT banned
				FROM users
				WHERE id = " . $userId;
		$result = $this->fireQuery($query);
		$value = $this->fetchAssoc($result);
		return $value;
	}

	/**
	 * Get active mobile operators
	 */
	function checkOperator($operatorId) {
		$query =
			"SELECT *
				FROM operators
				WHERE id = " . $operatorId . " AND status = " . ACTIVE;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);

		if ($value == 0) {
			return $value;
		}
		else {
			$resultArr = $this->fetchAssoc($result);
			return $resultArr;
		}
	}

	/**
	 * Check a user's available balance
	 */
	function checkBalance($userId) {
		$query =
			"SELECT balance
				FROM users
				WHERE id = " . $userId;
		$result = $this->fireQuery($query);
		$value = $this->fetchAssoc($result);
		return $value;
	}

}

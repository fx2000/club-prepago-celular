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
include "../Dbconn.php";

class RequestRegisterAPI extends Dbconn {

	/**
	 * Register a new reseller
	 */
	function Register($data) {
		$date = date('Y-m-d H:i:s');

		// Check for user type and assign discount percentage
		if ($data['PlatformId'] == 2) {
			$discountRate = $this->checkDiscount();
		} else {
			$discountRate = 0;
		}

		// Get welcome reward value
		$points = $this->checkRewards();

		// If a sponsor was specified, check if it is active and not deleted
		if ($data['SponsorId']) {
			$sponsorValid = $this->checkSponsor($data['SponsorId']);

			// If it's a valid sponsor, set Sponsor ID
			if ($sponsorValid == 1) {
				$sponsor = $data['SponsorId'];

			// Otherwise assign the reseller to the default sponsor
			} else {
				$sponsor = DEFAULT_SPONSOR;
			}

		// Otherwise assign the reseller to the default sponsor
		} else {
			$sponsor = DEFAULT_SPONSOR;
		}

		// Insert new user into users table
		$query =
			"INSERT INTO users (
				user_type,
				name,
				tax_id,
				address,
				city,
				state,
				country_id,
				email,
				password,
				sponsor_id,
				discount_rate,
				phone_number,
				points,
				registered,
				x,
				y
			) VALUES (" .
				$data['PlatformId'] . "," .
				"\"" . $data['Name'] . "\"" . "," .
				"\"" . $data['TaxId'] . "\"" . "," .
				"\"" . $data['Address'] . "\"" . "," .
				"\"" . $data['City'] . "\"" . "," .
				"\"" . $data['Province'] . "\"" . "," .
				$data['Country'] . "," .
				"\"" . $data['Email'] . "\"" . "," .
				"\"" . sha1($data['Password'].SALT) . "\"" . "," .
				$sponsor . "," .
				$discountRate . "," .
				"\"" . $data['Phone_Number'] . "\"" . "," .
				"\"" . $points . "\"" . "," .
				"\"" . $date . "\"" . "," .
				"\"" . $data['longitude'] . "\"" . "," .
				"\"" . $data['latitude'] . "\"" .
			")";
		$result = $this->fireQuery($query);

		if ($result) {
			//$userId = mysql_insert_id();
			$userId = mysqli_insert_id($this->_conn);

			// Add user's current device to the devices table
			if ($userId) {
				$insDevice = $this->fireQuery(
					"INSERT INTO devices (
						user_id,
						device_id,
						platform_id,
						login_status
					) VALUES (" .
						$userId . "," .
						$data['DeviceId'] . "," .
						$data['PlatformId'] . "," .
						SIGNED_IN .
					")"
				);
			}

			// Generate activation URL
			$enc_uid = sha1($userId);
			$activation_url = DOMAINURL . 'home/activate/' . $enc_uid;

			// Generate account activation email
			$mail = new PHPMailer(true);

			// Select email template and pass variables
			$messageBody = file_get_contents(TEMPLATE_DIR . '/activacion.html');
			$messageBody = str_replace('%username%', $data['Name'], $messageBody);
			$messageBody = str_replace('%url%', $activation_url, $messageBody);

			// Set PHP Mailer parameters
			$mail->isSMTP();
			$mail->Host = EMAIL_SERVER;
			$mail->SMTPAuth = true;
			$mail->Username = EMAIL_USER;
			$mail->Password = EMAIL_PASSWORD;
			$mail->From = EMAIL_FROM;
			$mail->FromName = EMAIL_SENDER_NAME;
			$mail->addAddress($data['Email'], $data['Name']);
			$mail->Port = 465;
			$mail->Timeout = 30;
			$mail->WordWrap = 50;
			$mail->isHTML(true);
			$mail->CharSet = "UTF-8";
			$mail->Subject = 'Bienvenido a Club Prepago Celular';
			$mail->Body = $messageBody;

			// Prepare registration results
			$resultArr['UserId'] = $userId;
			$resultArr['Name'] = $data['Name'];

			// Return registration results
			if (!$mail->send()) {
				return $resultArr;
			} else {
				return $resultArr;
			}
		}
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

	// Check if sponsor exists and is active
	function checkSponsor($sponsorId) {
		$query =
			"SELECT id
				FROM sponsors
				WHERE id = " . $sponsorId . " AND delete_status = " . NOT_DELETED . " AND status = " . ACTIVE;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
		return $value;
	}

	/*
	 * Check if email address is assigned to a valid user
	 */
	function checkEmail($email) {
		$query =
			"SELECT id
				FROM users
				WHERE email = \"" . $email . "\"" . " AND delete_status = " . NOT_DELETED;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
		return $value;
	}

	// Check default discount rate for resellers
	function checkDiscount() {
		$query =
			"SELECT discount_rate
				FROM settings";
		$result = $this->fireQuery($query);
		$value = $this->fetchAssoc($result);
		return $value['discount_rate'];
	}

	// Get initial reward points value
	function checkRewards() {
		$query =
			"SELECT reward_signup
				FROM settings";
		$result = $this->fireQuery($query);
		$value = $this->fetchAssoc($result);
		return $value['reward_signup'];
	}
}

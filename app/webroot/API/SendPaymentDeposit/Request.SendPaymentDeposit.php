<?php
/**
 * Send Payment notification (Bank deposit)
 *
 * Club Prepago API
 *
 * All taxes and discount rates for direct deposit payments are calculated by the
 * Payments controller once the payment notification is approved
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.SendPaymentDeposit
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../Dbconn.php";

class RequestSendPaymentDepositAPI extends Dbconn {

	/*
	 * Send a new payment notification for review
	 */
	function sendPaymentDeposit($data) {

		// Set payment details
		$userId = $data['UserId'];
		$amount = round($data['amount'], 2);
		$date = date('Y-m-d H:i:s', time());
		$userData = $this->getUserData($userId);
		$name = $userData['name'];

		// Insert payment information into payment notifications table
		$selPaymentDeposit =
			"INSERT INTO payments (
				user_id,
				user_type,
				payment_method,
				bank_id,
				reference_number,
				amount,
				notification_date,
				x,
				y
			) VALUES (" .
				$userId	. "," .
				$data['PlatformId'] . "," .
				PAYMENT_BANK . "," .
				$data['BankId'] . "," .
				"\"" . $data['reference_number'] . "\"" . "," .
				$amount	. "," .
				"\"" . $date . "\"" . "," .
				"\"" . $data['latitude'] . "\"" . "," .
				"\"" . $data['longitude'] . "\"" .
			")";
		$resPaymentDeposit = $this->fireQuery($selPaymentDeposit);
		$paymentId = mysqli_insert_id($this->_conn);

		// Set user type name
		if ($data['PlatformId'] == 1) {
			$type = 'Usuario';
		} else if ($data['PlatformId'] == 2) {
			$type = 'Revendedor';
		} else {
			$type = $data['PlatformId'];
		}

		// If all went well, generate notification email
		if ($resPaymentDeposit) {

			// Generate payment notifications url
			$url = DOMAINURL . "/admin/payments/details/" . base64_encode($paymentId);

			// Generate payment notification email
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
			$mail->Subject = 'Nueva notificación de pago en efectivo, depósito o transferencia';
			$mail->Body =
					"<html>
					<body>
						<div style='font-family:Tahoma;'>
							Hay una nueva notificación de pago en efectivo, depósito o transferencia pendiente por revisión:<br/><br/>
							<span style='font-size:12px;'><b>Tipo: </b>" . $type . "</span><br/>
							<span style='font-size:12px;'><b>Nombre: </b>" . $name . "</span><br/>
							<span style='font-size:12px;'><b>Monto: </b> B/. " . number_format((float)$amount, 2, '.', '') . "</span><br/>
							<span style='font-size:12px;'><b>Número de Pago: </b>" . str_pad($paymentId, 6, '0', STR_PAD_LEFT) . "</span><br/><br/>
							<a href=" . $url . ">Haz Click Aquí</a> para ingresar al sistema.<br/><br/>
							Gracias,<br/><br/>
							<b>Club Prepago Celular</b>
						</div>
					</body>
					<html>";

			if (!$mail->send()) {
				return 0;
			}
			return 1;
		} else {
			return 0;
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
	 * Get user's data
	 */
	function getUserData($userId) {
		$query =
			"SELECT *
				FROM users
				WHERE id = " . $userId;
		$result = $this->fireQuery($query);
		$value = $this->fetchAssoc($result);
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

	/*
	 * Check Bank ID
	 */
	function checkBank($bankId) {
		$query =
			"SELECT id
				FROM banks
				WHERE id = " . $bankId . " AND delete_status = " . NOT_DELETED;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
		return $value;
	}
}

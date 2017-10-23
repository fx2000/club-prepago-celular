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
include "../../APIConfig/Dbconn.php";

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
		$type = $userData['user_type'];

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
				$type . "," .
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

		// If all went well, generate notification email
		if ($resPaymentDeposit) {

			// Generate payment notifications url
			$url = DOMAINURL . "/admin/payments/details/" . base64_encode($paymentId);

			// Generate payment notification email
			$mail = new PHPMailer(true);

			// Set PHP Mailer parameters
			$mail->isSMTP();
			$mail->Host = EMAIL_SERVER;
			$mail->Port = EMAIL_PORT;
			$mail->Timeout = 30;
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
							<span style='font-size:12px;'><b>Nombre: </b>" . $name . "</span><br/>
							<span style='font-size:12px;'><b>Monto: </b> Bs. " . number_format((float)$amount, 2, '.', '') . "</span><br/>
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

			// Generate Payment Received email
			$mail = new PHPMailer(true);

			// Get Bank information
			$queryBank =
				"SELECT *
					FROM banks
					WHERE id = " . $data['BankId'] . " AND delete_status = " . NOT_DELETED;
			$resultBank = $this->fireQuery($queryBank);
			$valueBank = $this->fetchAssoc($resultBank);

			// Translate account type
			if ($valueBank['account_type'] == 1) {
				$bankType = "Corriente";
			} else {
				$bankType = "Ahorros";
			}

			// Calculate ITBMS and net amount
			$amount_net = $amount / 1.07;
			$itbms = $amount - $amount_net;


			// Select email template and pass variables
			$messageBody = file_get_contents(TEMPLATE_DIR . '/paymentreceived_deposit.html');
			$messageBody = str_replace('%username%', $userData['name'], $messageBody);
			$messageBody = str_replace('%payment_number%', str_pad($paymentId, 7, "0", STR_PAD_LEFT), $messageBody);
			$messageBody = str_replace('%notification_date%', date('d-m-Y h:i:s a', strtotime($date)), $messageBody);
			$messageBody = str_replace('%amount_total%', number_format((float)$amount, 2, '.', ''), $messageBody);
			$messageBody = str_replace('%itbms%', number_format((float)$itbms, 2, '.', ''), $messageBody);
			$messageBody = str_replace('%amount_net%', number_format((float)$amount_net, 2, '.', ''), $messageBody);
			$messageBody = str_replace('%razon%', "Club Prepago Celular, C.A.", $messageBody);
			$messageBody = str_replace('%banco%', $valueBank['bank_name'], $messageBody);
			$messageBody = str_replace('%cuenta%', $valueBank['account_number'], $messageBody);
			$messageBody = str_replace('%tipo%', $bankType, $messageBody);

			// Set PHP Mailer parameters
			$mail->isSMTP();
			$mail->Host = EMAIL_SERVER;
			$mail->SMTPAuth = true;
			$mail->Username = EMAIL_USER;
			$mail->Password = EMAIL_PASSWORD;
			$mail->From = EMAIL_FROM;
			$mail->FromName = EMAIL_SENDER_NAME;
			$mail->addAddress($userData['email'], $userData['name']);
			$mail->Port = 465;
			$mail->Timeout = 30;
			$mail->WordWrap = 50;
			$mail->isHTML(true);
			$mail->CharSet = "UTF-8";
			$mail->Subject = 'Hemos recibido tu solicitud correctamente';
			$mail->Body = $messageBody;

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
				WHERE device_id = " . $deviceId . " AND user_id = " . $userId . " AND login_status = " . SIGNED_IN;
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

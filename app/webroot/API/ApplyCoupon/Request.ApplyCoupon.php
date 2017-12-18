<?php
/**
 * Apply Cupon
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.ApplyCoupon
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../../APIConfig/Dbconn.php";

class ApplyCouponAPI extends Dbconn {

	/**
	 * Get user's coupon history
	 */
	function applyCoupon($data) {

		$date = date('Y-m-d H:i:s');
    $response = array();
		$status = 1;

		//Select coupon from coupon_redemptions table
		$update = "UPDATE coupon_redemptions
							SET status = " . "\"" . $status . "\"" . "," .
							" redeem_date = " . "\"" . $date . "\"" .
							" WHERE coupon_id = " . $data['CouponId'] .
							" AND reference_no = " . $data['ReferenceNo'];

		$applyCoupon = $this->fireQuery($update);

		// If the coupon was updated
		if ($applyCoupon) {

			$couponData = $this->getCouponData($data['CouponId'], $data['ReferenceNo']);
			$response['Name'] = $couponData['name'];

			// //Generate Purchase Coupon notifications url
			// $url = DOMAINURL . "/admin/purchaseCoupons/details/" . base64_encode($purchaseId);
			//
			// // Generate payment notification email
			// $mail = new PHPMailer(true);
			//
			// // Set PHP Mailer parameters
			// $mail->isSMTP();
			// $mail->Host = EMAIL_SERVER;
			// $mail->Port = 465;
			// $mail->Timeout = 30;
			// $mail->SMTPSecure = 'ssl';
			// $mail->SMTPAuth = true;
			// $mail->Username = EMAIL_USER;
			// $mail->Password = EMAIL_PASSWORD;
			// $mail->From = EMAIL_FROM;
			// $mail->FromName = EMAIL_SENDER_NAME;
			// $mail->addAddress(EMAIL_STAFF);
			// $mail->WordWrap = 50;
			// $mail->isHTML(true);
			// $mail->CharSet = "UTF-8";
			// $mail->Subject = 'Nueva notificación de Aplicación de Cupón';
			// $mail->Body =
			// 		"<html>
			// 		<body>
			// 			<div style='font-family:Tahoma;'>
			// 				Hay una nueva notificación de Aplicación de Cupón:<br/><br/>
			// 				<span style='font-size:12px;'><b>Nombre: </b>" . $username . "</span><br/>
			// 				<span style='font-size:12px;'><b>Monto: </b> B/. " . number_format((float)$amount, 2, '.', '') . "</span><br/>
			// 				<span style='font-size:12px;'><b>Número de Compra: </b>" . str_pad($purchaseId, 6, '0', STR_PAD_LEFT) . "</span><br/><br/>
			// 				<a href=" . $url . ">Haz Click Aquí</a> para ingresar al sistema.<br/><br/>
			// 				Gracias,<br/><br/>
			// 				<b>Club Prepago Celular</b>
			// 			</div>
			// 		</body>
			// 		<html>";
			//
			// if (!$mail->send()) {
			// 	return 0;
			// }
			//
			// // Generate Purchase Coupon email
			// $mail = new PHPMailer(true);
			//
      // // Select email template and pass variables
			// $messageBody = file_get_contents(TEMPLATE_DIR . '/purchase_coupon.html');
			// $messageBody = str_replace('%username%', $username, $messageBody);
			// $messageBody = str_replace('%purchaseId%', str_pad($purchaseId, 7, "0", STR_PAD_LEFT), $messageBody);
			// $messageBody = str_replace('%date%', date('d-m-Y h:i:s a', strtotime($date)), $messageBody);
			// $messageBody = str_replace('%amount%', number_format((float)$amount, 2, '.', ''), $messageBody);
      // $messageBody = str_replace('%due_date%', $due_date, $messageBody);
      // $messageBody = str_replace('%image%', $image, $messageBody);
			// $messageBody = str_replace('%description%', $description, $messageBody);
			//
			// // Set PHP Mailer parameters
      // $mail->isSMTP();
			// $mail->Host = EMAIL_SERVER;
			// $mail->Port = 465;
			// $mail->Timeout = 30;
      // $mail->SMTPSecure = 'ssl';
			// $mail->SMTPAuth = true;
			// $mail->Username = EMAIL_USER;
			// $mail->Password = EMAIL_PASSWORD;
			// $mail->From = EMAIL_FROM;
			// $mail->FromName = EMAIL_SENDER_NAME;
			// $mail->addAddress($email);
			// $mail->WordWrap = 50;
			// $mail->isHTML(true);
			// $mail->CharSet = "UTF-8";
			// $mail->Subject = 'Gracias por aplicar tu Cupón';
			// $mail->Body = $messageBody;
			//
			// if (!$mail->send()) {
			// 	return 0;
			// }

		}

		return $response;

	}

	/**
	 * Check User ID
	 */
	function checkUser($userId) {

    $query = "SELECT u.id FROM users u, users_stores us WHERE u.id = " . $userId . " AND u.id = us.user_id";
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
    return $value;

  }

  /**
	 * Check Device ID
	 */
	function checkDevice($deviceId, $userId) {

    $query = "SELECT id FROM devices WHERE device_id = " . $deviceId . " AND user_id = " . $userId .
             " AND login_status = " . SIGNED_IN;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
		return $value;

	}

	/**
	 * Check Check Coupon
	 */
	function checkCoupon($userId, $couponId, $referenceNo) {

    $query = "SELECT cr.id FROM coupon_redemptions cr, coupons c, users_stores us
							WHERE cr.coupon_id = c.id AND c.store_id = us.store_id AND us.user_id = " . $userId .
							" AND cr.coupon_id = " . $couponId .
							" AND cr.reference_no = " . $referenceNo .
							" AND cr.status = 0";
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
		return $value;

	}

	/**
	 * Get Coupon Data
	 */
	function getCouponData($couponId, $referenceNo) {

		$query = "SELECT u.name as name, u.email, c.amount, c.image, c.description
							FROM coupon_redemptions cr, coupons c, users u
							WHERE cr.coupon_id = c.id
							AND cr.user_id = u.id
							AND c.id = " . $couponId ."
							AND cr.reference_no = " . $referenceNo;
		$result = $this->fireQuery($query);
		$value = $this->fetchAssoc($result);
		return $value;

  }

}

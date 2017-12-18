<?php
/**
 * Purchase Coupon
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.PurchaseCoupon
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../../APIConfig/Dbconn.php";

class RequestPurchaseCouponAPI extends Dbconn {

  /**
	 * Generate a new Purchase Coupon
	 */
	function purchaseCoupon($data) {

		$date = date('Y-m-d H:i:s');
    $couponId = $data['CouponId'];
    $userId = $data['UserId'];
    $couponData = $this->getCouponData($couponId);
    $userData = $this->getUserData($userId);
    $userType = $userData['user_type'];
    $username = $userData['name'];
    $email = $userData['email'];
    $amount = $couponData['amount'];
    $points = $couponData['points'];
    $due_date = $couponData['due_date'];
    $image = $couponData['image'];
    $description = $couponData['description'];
    $reference_no = rand(0,9999999);

    // Declare the results array
		$resultArr = array();

    // Insert preliminary purchase information into coupon_redeptions table
    $insPurchase =
        "INSERT INTO coupon_redemptions (
          coupon_id,
          user_id,
          status,
          reference_no,
          purchase_date,
          x,
          y
        ) VALUES (" .
          $couponId . "," .
          $userId . "," .
          0 . "," .
          $reference_no . "," .
          "\"" . $date . "\"" . "," .
          "\"" . $data['Longitude'] . "\"" . "," .
          "\"" . $data['Latitude'] . "\"" .
        ")";

    $insPurchase = $this->fireQuery($insPurchase);
    $purchaseId = mysqli_insert_id($this->_conn);

    // If all went well, generate notification email
  	if ($insPurchase) {

      // Adjust the coupon's quantity
      $updCouponQua = $this->fireQuery("UPDATE coupons SET cant = cant - 1 WHERE id = " . $data['CouponId']);

      // Adjust the user's balance
      $updUserBal = $this->fireQuery("UPDATE users SET balance = balance - " . $amount ." WHERE id = " . $userId);

      // If not a reseller, adjust the user's points
      if ($userType == 1) {

        $updUserPoints = $this->fireQuery("UPDATE users SET points = points + " . $points . " WHERE id = ". $userId);

      }

      // // Generate Purchase Coupon notifications url
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
			// $mail->Subject = 'Nueva notificación de Compra de Cupón';
			// $mail->Body =
			// 		"<html>
			// 		<body>
			// 			<div style='font-family:Tahoma;'>
			// 				Hay una nueva notificación de Compra de Cupón:<br/><br/>
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
			// $mail->Subject = 'Hemos recibido tu solicitud de Compra';
			// $mail->Body = $messageBody;
      //
			// if (!$mail->send()) {
			// 	return 0;
			// }

      $resultArr['Data']['ReferenceNo'] = $reference_no;

      return $resultArr;

    } else {

     return 0;

   }

  }

  /**
   * Check User ID
   */
  function checkUser($userId) {

    $query = "SELECT id FROM users WHERE id = " . $userId;
    $result = $this->fireQuery($query);
    $value = $this->rowCount($result);
    return $value;

  }

  /**
   * Check Device ID
   */
  function checkDevice($deviceId, $userId) {

    $query = "SELECT id FROM devices
              WHERE device_id = " . $deviceId . " AND user_id = " . $userId . " AND login_status = " . SIGNED_IN;
    $result = $this->fireQuery($query);
    $value = $this->rowCount($result);
    return $value;

  }

  /*
	 * Check Coupon ID
	 */
	function checkCoupon($couponId) {

		$query = "SELECT id FROM coupons WHERE id = " . $couponId . " AND status = 1 AND delete_status = " . NOT_DELETED;
		$result = $this->fireQuery($query);
		$value = $this->rowCount($result);
		return $value;

  }

  /**
   * Check a user's available balance
  */
  function checkBalance($userId) {

    $query = "SELECT balance FROM users WHERE id = " . $userId;
    $result = $this->fireQuery($query);
    $value = $this->fetchAssoc($result);
    return $value;

  }

  /*
	 * Get coupon's data
	 */
	function getCouponData($couponId) {

		$query = "SELECT * FROM coupons WHERE id = " . $couponId . " AND status = 1 AND delete_status = " . NOT_DELETED;
    $result = $this->fireQuery($query);
		$value = $this->fetchAssoc($result);
		return $value;

  }

  /**
	 * Get user's data
	 */
	function getUserData($userId) {

		$query = "SELECT * FROM users WHERE id = " . $userId . " AND status = 1 AND delete_status = 0";
		$result = $this->fireQuery($query);
		$value = $this->fetchAssoc($result);
		return $value;

  }
}

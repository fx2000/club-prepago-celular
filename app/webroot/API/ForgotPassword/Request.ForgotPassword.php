<?php
/**
 * Forgot Password
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.ForgotPassword
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../../APIConfig/Dbconn.php";

class RequestForgotPasswordAPI extends Dbconn {

	/*
	 * Request a password reset
	 */
	function forgotPassword($data) {

		// Generate new password
		$pwd = $this->generatePassword();

		// Select appropriate user from users table
		$selquery =
			"SELECT *
				FROM users
				WHERE email = \"" . $data['Email'] . "\" AND delete_status = " . NOT_DELETED;
		$resultUser = $this->fireQuery($selquery);
		$arrUser = $this->fetchAssoc($resultUser);

		// Enter new password into users table
		$query =
			"UPDATE users
				SET password = \"" . sha1($pwd.SALT) . "\" WHERE id = " . $arrUser['id'];
		$result = $this->fireQuery($query);

		// If everything goes well, send a notification email to the user
		if ($result) {

			// Generate account activation email
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
			$mail->addAddress($arrUser['email'], $arrUser['name']);
			$mail->WordWrap = 50;
			$mail->isHTML(true);
			$mail->CharSet = "UTF-8";
			$mail->Subject = 'Tus contraseña ha sido reestablecida';
			$mail->Body =
				'<html>
				<body>
					<font face="Tahoma">
						Hola ' . $arrUser['name'] . ",<br/><br/>
						Tu contraseña ha sido reestablecida con éxito. Tus nuevos datos de acceso son:<br/><br/>
						<span style='font-size:12px;'><b>Usuario: </b> " . $arrUser['email'] . " </span><br/>
						<span style='font-size:12px;'><b>Contraseña: </b> " . $pwd . " </span><br/><br/>
						Si no solicitaste una nueva contraseña, por favor escíbenos a <a href=\"mailto:support@clubprepago.com\">support@clubprepago.com</a></br>
						o llámanos al <b>+507 388-6220</b><br/><br/>
						Gracias,<br/><br/>
						<b>Club Prepago Celular</b>
					</font>
				</body>
				<html>";

			if (!$mail->send()) {
				return 0;
			} else {
				return 1;
			}
		} else {
			return 0;
		}
	}

	/*
	 * Generate a new password
	 */
	function generatePassword() {

		// Set the random id length
		$random_id_length = 8;

		// Generate a random id, encrypt it, and store it in $rnd_id
		$rnd_id = crypt(uniqid(rand(), 1));

		// Remove any slashes that might have come
		$rnd_id = strip_tags(stripslashes($rnd_id));

		// Remove any . or / and reverse the string
		$rnd_id = str_replace(".", "", $rnd_id);
		$rnd_id = strrev(str_replace("/", "", $rnd_id));

		// Take the first 10 characters from the $rnd_id
		$rnd_id = substr($rnd_id, 0, $random_id_length);

		// Shuffle characters
		$rnd_id = str_shuffle($rnd_id);

		// Remove caps
		$rnd_id = strtolower($rnd_id);

		// Return generated password
		return $rnd_id;
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
}

<?php
/**
 * Validation Component
 * Email, username and password validation, password generator
 *
 * @package       app.Controller.Component
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
App::uses('Component', 'Controller');

class ValidationComponent extends Component {
	var $errors;
	
	/*
	 * Validate presence of data
	 */
	function Presence($inputData) {
		
		if ($inputData == '' || $inputData == ' ') {
			return true;
		}
		return false;
	}

	/*
	 * Validate email address format
	 */
	function Email($inputData)
	{
		$EMAIL_REG_EXP	= "/^[a-zA-Z0-9.]+[a-zA-Z0-9._-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/";
		
		if (!preg_match($EMAIL_REG_EXP, $inputData)) {
			return true;
		}
		return false;
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
	 * Generate error message
	 */
	function error ($message) {
			
		if (!is_array($this->errors)) {
			$this->errors = array();
		}
		array_push($this->errors, $message);
	}
}

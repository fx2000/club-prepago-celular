<?php
/**
 * Get User Info for Transfer Usd to Bsf
 *
 * Club Prepago API
 *
 * @copyright     Club Prepago Celular(tm) Project
 * @package       API.GetUserUsdBsf
 * @since         Club Prepago Celular(tm) v 1.1.0
 */
include "../../APIConfig/Dbconn.php";

class RequestGetUserUsdBsfAPI extends Dbconn {

	/**
	 * Get User information
	 */
	function getUserInfo($data) {

		// Select data from users table
		$selUser =
			"SELECT u.*,c.name As country
				FROM users As u,countries AS c
				WHERE c.id = u.country_id AND u.email = \"" .
			$data['Email'] . "\" ";
		$resUser = $this->fireQuery($selUser);
		$arrUser = $this->fetchAssoc($resUser);

		// Fill response array
		$users['Name'] = $arrUser['name'];
		$users['ID'] = $arrUser['tax_id'];
		$users['Email'] = $arrUser['email'];
		$users['Address'] = $arrUser['address'];
		$users['Country'] = $arrUser['country'];
		$users['City'] = $arrUser['city'];
		$users['Province'] = $arrUser['state'];
		$users['PhoneNo'] = $arrUser['phone_number'];
    $users['Amount'] = (double)filter_var($data['Amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) * checkBSF();

		// Fill the User ID with zeroes for cosmetic reasons
		$remaining = 6 - strlen($arrUser['id']);
		$userId = '';

		for ($i = 0; $i < $remaining; $i++) {
			$userId .= '0';
		}
		$userId .= $arrUser['id'];

		// Continue filling the response array
		$users['UserId'] = $userId;

		//Return profile information
		return $users;
	}

	/**
	 * Check Value BSF per USD
	 */
	function checkBSF() {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, 'https://s3.amazonaws.com/dolartoday/data.json');
    $result = curl_exec($ch);
    curl_close($ch);

    $obj = json_decode($result);
    return $obj->USD['tranferencia'];

	}
}

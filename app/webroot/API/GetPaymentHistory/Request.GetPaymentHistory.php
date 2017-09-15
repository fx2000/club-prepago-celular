<?php
/**
 * Get Payment History
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.GetpaymentHistory
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../../APIConfig/Dbconn.php";

class RequestGetpaymentHistoryAPI extends Dbconn {

	/**
	 * Get user's payment history
	 */
	function getpaymentHistory($data) {
		$paymentHistory = array();
		$bank_name = "";

		// Select payment from payments table
		$selpaymentHistory =
			"SELECT *
				FROM payments
				WHERE user_id = " . $data['UserId'] . " ORDER BY id desc";
		$respaymentHistory = $this->fireQuery($selpaymentHistory);
		$numpaymentHistory = $this->rowCount($respaymentHistory);

		// If there are payments on the list, return them all
		if ($numpaymentHistory > 0) {
			$i = 0;

			while ($arrpaymentHistory = $this->fetchAssoc($respaymentHistory)) {
				$paymentHistory[$i]['id'] = $arrpaymentHistory['id'];
				$paymentHistory[$i]['payment_method'] = $arrpaymentHistory['payment_method'];
				$paymentHistory[$i]['reference_number'] = str_pad($arrpaymentHistory['reference_number'],6,"0",STR_PAD_LEFT);
				$paymentHistory[$i]['amount']	= $arrpaymentHistory['amount'];
				$paymentHistory[$i]['discount']	= $arrpaymentHistory['discount'];
				$paymentHistory[$i]['tax']	= $arrpaymentHistory['tax'];
				$paymentHistory[$i]['net_amount']	= $arrpaymentHistory['net_amount'];
				$paymentHistory[$i]['amount_credited']	= $arrpaymentHistory['amount_credited'];
				$paymentHistory[$i]['status'] = $arrpaymentHistory['status'];
				$paymentHistory[$i]['date'] = $arrpaymentHistory['notification_date'];

				// Translate Bank ID into bank name
				$bankId = $arrpaymentHistory['bank_id'];
				$selQry =
					"SELECT *
						FROM banks
						WHERE id = " . $bankId . " AND delete_status = " . NOT_DELETED . " LIMIT 1";
				$resQry = $this->fireQuery($selQry);
				$numBanks = $this->rowCount($resQry);

				if ($numBanks > 0) {
					$bank = $this->fetchAssoc($resQry);
					$bank_name = $bank['bank_name'];
				}
				$paymentHistory[$i]['bank'] = $bank_name;

				$i++;
			}
			return $paymentHistory;
		} else {
			return $numpaymentHistory;
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
}

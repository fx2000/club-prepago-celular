<?php
/**
 * Login
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.Login
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include "../../APIConfig/Dbconn.php";

class RequestLoginAPI extends Dbconn {

    /**
     * Login
     */
    function login($data) {

        // Declaring variables
        $resArray = array();

        // Check email address and password in users table
        $query =
            "SELECT *
				FROM users
				WHERE email = " . "\"" . $data['Email'] . "\"" .
            " AND password = " . "\"" . sha1($data['Password'].SALT) . "\"" .
            " AND delete_status = " . NOT_DELETED . "";
        $result = $this->fireQuery($query);
        $num = $this->rowCount($result);

        // If no matching email/password is found, return an error
        if ($num == 0) {
            return 0;
        } else {
            $userdata = $this->fetchAssoc($result);

            // Check if user is marked as inactive
            if ($userdata['status'] == INACTIVE) {
                return 1;

                // Check if user has verified his email address
            } else if ($userdata['email_verify'] == NOT_VERIFIED) {
                return 2;

                // Check if user is banned
            } else if ($userdata['banned'] == BANNED) {
                return 3;

                // If all goes well, proceed
            } else {

                // Check the devices table for the current user's latest device
                $selDevice =
                    "SELECT id
						FROM devices
						WHERE user_id = " . $userdata['id'] . " AND device_id = " . $data['DeviceId'] . "";
                $resDevice = $this->fireQuery($selDevice);
                $numDevice = $this->rowCount($resDevice);

                // If the user doesn't have a registered device, insert the current device's information
                if ($numDevice == 0) {
                    $insDevice = $this->fireQuery(
                        "INSERT INTO devices (
							user_id,
							device_id,
							platform_id,
							login_status
						) VALUES (" .
                        $userdata['id'] . "," .
                        $data['DeviceId'] . "," .
                        $data['PlatformId'] ."," .
                        SIGNED_IN .
                        ")"
                    );

                    // If they do, update it with the current device
                } else {
                    $updDevice = $this->fireQuery(
                        "UPDATE devices
							SET login_status = " . SIGNED_IN .
                        " WHERE user_id = " . $userdata['id'] ." AND device_id = " . $data['DeviceId'] .
                        " AND platform_id = " . $data['PlatformId']
                    );
                }

                // Set reseller's country to obtain tax information
                $selCountry =
                    "SELECT *
						FROM countries
						WHERE id = " . $userdata['country_id'];
                $resCountry = $this->fireQuery($selCountry);
                $arrCountry = $this->fetchAssoc($resCountry);

                // Send the appropriate user information back
                $resArray['UserId'] = $userdata['id'];
                $resArray['Name'] = $userdata['name'];
                $resArray['UserType'] = $userdata['user_type'];
                $resArray['DiscountPercentage'] = $userdata['discount_rate'];
                $resArray['Country'] = $arrCountry['name'];
                $resArray['TaxPercentage'] = $arrCountry['tax'];
                $resArray['PrepaidBalance'] = $userdata['balance'];
                $resArray['TaxId'] = $userdata['tax_id'];
                $resArray['city'] = $userdata['city'];
                $resArray['state'] = $userdata['state'];
                $resArray['RewardPoint'] = $userdata['points'];
                return $resArray;
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

    /**
     * Get latest app version number
     */
    function getLatestVersion($platformId) {
        $query =
            "SELECT version
				FROM versions
				WHERE id = " . $platformId;
        $result = $this->fireQuery($query);
        $value = $this->fetchAssoc($result);
        return $value['version'];
    }
}

<?php
/**
 * API Config
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API
 * @since         Club Prepago Celular(tm) v 1.0.0
 */

// Server URL
define('DOMAINURL','http://test.clubprepago.com/');

// Directories
define('LOG_DIR', '/var/www/app/log');
define('TEMPLATE_DIR', '/var/www/app/View/Emails/html');

// Set email parameters
define('EMAIL_SERVER', 'ssl://pan.movilesdepanama.com');
define('EMAIL_FROM', 'noreply@clubprepago.com');
define('EMAIL_USER', 'noreply@clubprepago.com');
define('EMAIL_PASSWORD', 'noreplyClub2049');
define('EMAIL_SENDER_NAME', 'Club Prepago Celular');
define('EMAIL_STAFF', 'staff@clubprepago.com');

// Set API type
define('API_TYPE', 1);				// Club Prepago Empresarios
define('API_USER_TYPE', 1);			// Reseller
define('APP_CODE', 1);				// Club Prepago Empresarios

// Set Payment Methods
define('PAYMENT_BANK', 1);			// Direct bank deposit
define('PAYMENT_CC', 2);			// Credit card
define('PENDING', 0);				// Payment pending approval
define('APPROVED', 1);				// Payment approved
define('DENIED', 2);				// Payment denied

// Set Recharge Payment Methods
define('RECHARGE_BALANCE', 1);		// Prepaid balance
define('RECHARGE_CC', 2);			// Credit card
define('RECHARGE_POINTS', 3);		// Reward Points

// Defaults
define('DEFAULT_SPONSOR', 1);		// Club Prepago Celular
define('SIGNED_IN', 1);				// Signed in
define('SIGNED_OUT', 0);			// Signed out
define('ACTIVE', 1);				// Active
define('INACTIVE', 0);				// Inactive
define('DELETED', 1);				// Deleted
define('NOT_DELETED', 0);			// Not deleted
define('BANNED', 1);				// Banned
define('NOT_BANNED', 0);			// Not banned
define('VERIFIED', 1);				// Email verified
define('NOT_VERIFIED', 0);			// Email not verified

// Security
define('SALT', 'HrJiOSKXMuf7VR2syu2wv83iDUoCo3V5og2SavLF822LbAxA8GYx1kOAOWXambo8'); // SHA1 salt

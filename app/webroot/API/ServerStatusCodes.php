<?php
/**
 * Server Status Codes
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
include('config.php');

class ServerStatusCode {
	
	public static function getStatusCodeMessage($status) {
		$codes = Array(
			500 => 'XML not formatted correctly',
			501 => 'Incorrect parameters',											//
			502 => 'Device ID parameter not found',									//
			503 => 'Device ID parameter cannot be empty',							//
			504 => 'Name parameter not found',										//
			505 => 'Name parameter cannot be empty',								//
			506 => 'Invalid Name',
			507 => 'Please Enter name',
			508 => 'Email Address parameter not found',								//
			509 => 'Email Address parameter cannot be empty',						//
			510 => 'Invalid Email address',											//
			511 => 'Username parameter is not found',
			512 => 'Enter Username',
			513 => 'Invalid Username',
			514 => 'New Password parameter not found',								//
			515 => 'New Password parameter cannot be empty',						//
			516 => 'New Password must have at least 6 characters',					//
			517 => 'Address parameter not found',									//
			518 => 'Address parameter cannot be empty',								//
			519 => 'Country parameter is not found',
			520 => 'Enter Country',
			521 => 'Phone Number parameter not found',								//
			522 => 'Phone Number parameter cannot be empty',						//
			523 => 'Invalid Phone Number',											//
			524 => 'Mode Of Payment parameter is not found',
			525 => 'Mode Of Payment should be 1 or 2',
			526 => 'Platform parameter cannot be empty',
			527 => 'Platform does not exist',										//
			528 => 'Unable to obtain country list',									//
			529 => 'Email address already registered',								//
			530 => 'username already exists',
			531 => 'ID parameter not found',
			532 => 'Enter ID',
			533 => 'Success',														//
			534 => 'User could not be added',
			535 => 'Incorrect Email or Password',									//
			536 => 'User is inactive',												//
			537 => 'Please verify your account before signing in',					//
			538 => 'Mobile Operator parameter not found',							//
			539 => 'Mobile Operator Name parameter cannot be empty',				//
			540 => 'User ID parameter not found',									//
			541 => 'User ID parameter cannot be empty',								//
			542 => 'Invalid User ID',												//
			543 => 'UserId does not exist',											//
			544 => 'User is not signed in on this device',							//
			545 => 'Phone number already in Favorites',								//
			546 => 'Could not create new Favorite',									//
			547 => 'Your Favorites list is empty',									//
			548 => 'Favorite ID parameter not found',								//
			549 => 'Favorite ID parameter cannot be empty',							//
			550 => 'Invalid Favorite ID',											//
			551 => 'Favorite ID does not exist',									//
			552 => 'Favorite could not be removed',									//
			553 => 'Could not obtain Reward points',								//
			554 => 'Invalid or Inactive Mobile Operator',							//
			555 => 'Profile could not be updated',									//
			556 => 'Amount parameter not found',									//
			557 => 'Enter amount',
			558 => 'Invalid amount',
			559 => 'Payment Method parameter not found',							//
			560 => 'Payment Method parameter cannot be empty',						//
			561 => 'Invalid Payment Method',										//
			562 => 'User do not have enough points to redeem this reward',			//
			563 => 'Recharge has been successful',									//
			564 => 'Improper MerchantID',											//
			565 => 'Improper CustomerPhoneNo',										//
			566 => 'Improper MerchantPIN',											//
			567 => 'Amount is not valid (Should be more)',							//
			568 => 'Amount is not valid (Should be less)',							//
			569 => 'Operation not supported or data inconsistency',					//
			570 => 'Remote system unavailable',										//
			571 => 'Insufficient funds',											//
			572 => 'Duplicate Transaction',											//
			573 => 'Missing MerchantID, CustomerPhoneNo, MerchantPIN, TopupAmt',	//
			574 => 'Improper ProductID',											//
			575 => 'Merchant account has been disabled',							//
			576 => 'Improper Terminal',												//
			577 => 'Something went wrong',											//
			578 => 'Type should be 1 , 2 or 3',						
			579 => 'Invalid Favorite ID',											//	
			580 => 'Favorite ID parameter cannot be empty',							//
			581 => 'Current Password parameter not found',							//
			582 => 'Current Password parameter cannot be empty',					//
			583 => 'Current Password incorrect',									//
			584 => 'New password and old password do not match',
			585 => 'Password change failed',										//
			586 => 'Rewards list not found',
			587 => 'Unable to obtain recharge history',								//
			588 => 'Unable to obtain Settings from server',
			589 => 'Insufficient Balance',											//
			590 => 'Invalid Reward',
			591 => 'Email Address not registered',									//
			592 => 'A new password has been sent to your email address',			//
			593 => 'Password reset failed',
			594 => 'Unable to obtain list of banks',								//
			595 => 'You have not made any payments',
			596 => 'Bank ID parameter not found',									//
			597 => 'Invalid Bank ID',												//
			598 => 'Bank ID parameter cannot be empty',								//
			599 => 'Reference Number parameter cannot be empty',					//
			600 => 'Bank does not exist',
			601 => 'Amount parameter cannot be empty',								//
			602 => 'Invalid amount',												//
			603 => 'Transaction ID parameter cannot be empty',						//
			604 => 'Transaction Status parameter not found',						//
			605 => 'Invalid Transaction Status',									//
			606 => 'Transaction Failed',											//
			607 => 'This mobile operator is not available at the moment, our support staff is working on the problem and will make sure the problem is fixed as soon as possible. Please feel free to contact us at +507 388-6220 or support@clubprepago.com if you have any questions. We apologize for the inconvenience',
			608 => 'Your credit card or account has been debited but the recharge transaction has failed. Our support staff is working on the problem and will make sure you receive your recharge as soon as possible. Please take note of this transaction\'s Reference Number and feel free to contact us at +507 388-6220 or support@clubprepago.com if you have any questions. We apologize for the Inconvenience',
			609 => 'This account has been banned',									//
			610 => 'Unable to send payment notification',							//
			611 => 'Unable to obtain profile information',							//
			612 => 'Password parameter not found',									//
			613 => 'Password parameter cannot be empty',							//
			614 => 'Country parameter cannot be empty',
			700 => 'Sponsor ID parameter not found',
			701 => 'Sponsor ID parameter cannot be empty',
			702 => 'Unable to obtain Sponsor information',							//	
			703 => 'Sponsor not found',
			704 => 'Discount parameter not found',									//
			705 => 'Discount parameter cannot be empty',							//	
			706 => 'Invalid Discount',												//
			707 => 'Tax parameter not found',										//	
			708 => 'Tax parameter cannot be empty',									//
			709 => 'Invalid Tax',													//
			710 => 'Tax ID parameter not found',									//
			711 => 'Tax ID parameter cannot be empty',
			712 => 'City parameter not found',										//	
			713 => 'City parameter cannot be empty',								//
			714 => 'Province parameter not found',									//	
			715 => 'Province parameter cannot be empty'								//
		);
		return (isset($codes[$status])) ? $codes[$status] : '';
	}
}

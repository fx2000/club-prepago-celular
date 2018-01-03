<?php
/**
 * Payments Controller
 *
 * This file handles user and reseller payment notifications,
 * approvals and denials.
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.Controller
 * @since         Club Prepago Celular(tm) v 1.0.0
 */

App::uses('CakeEmail', 'Network/Email');
include '../../webroot/API/CreditCardProcessors/instapago.php';

class PaymentsController extends AppController {

	var $uses = array(
		'User',
		'Recharge',
		'Redemption',
		'Admin',
		'AccountHistory',
		'Payment',
		'Bank',
		'Setting',
		'Invoice'
	);
	var $components = array('Validation');

	/**
	 * Check user payment requests
	 */
	public function admin_payment_notifications() {

		// Check that the session is active
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Load Payment model
		$this->loadModel('Payment');

		// Find data for tables and sort by id
		$data = $this->Payment->find(
			'all',
			array(
				'conditions' => array(
					'status'         => 0
				),
				'order'      => 'id desc'
			)
		);
		$this->set('paymentdata', $data);

		// Check current user
		$adminData = $this->Admin->find(
			'first',
			array(
				'conditions' => array('id' => $this->Session->read('admin_id'))
			)
		);
		$this->set('Admindata', $adminData);
	}

	/**
	 * Check payment request history
	 */
	public function admin_payment_history() {

		// Check that the session is active
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Load Payment model
		$this->loadModel('Payment');

		// Find data for tables and sort by id
		$data = $this->Payment->find(
			'all',
			array(
				'conditions' => array(
					'status !=' => '0',
				),
				'order'      => 'id desc'
			)
		);
		$this->set('paymentdata', $data);

		// Get session details
		$admindata = $this->Admin->find(
			'first',
			array(
				'conditions' => array('id' => $this->Session->read('admin_id'))
			)
		);
		$this->set('Admindata', $admindata);
	}

	/**
	 * Accept user payments
	 */
	public function admin_approve($id) {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// No layout
		$this->layout = '';

		// Load models
		$this->loadModel('Country');

		if (!empty($id)) {

			// Set basic payment information
			$data['Payment']['id'] = base64_decode($id);
			$data['Payment']['status'] = 1;
			$data['Payment']['change_status_date'] = date('Y-m-d H:i:s',time());

			// Generate promo number for lottery promotions
			$prePromo = rand(0,9999);
			$promo = str_pad($prePromo, 4, "0", STR_PAD_LEFT);
			$data['Payment']['promo_number'] = $promo;


			// Find payment details
			$paymentData = $this->Payment->find(
				'first',
				array(
					'conditions' => array('id' => base64_decode($id))
				)
			);
			$userID = $paymentData['Payment']['user_id'];

			// Get user's details
			$userData = $this->User->find(
				'first',
				array(
					'conditions' => array('id' => $userID)
				)
			);

			$arrRefNo = explode(':', $paymentData['Payment']['reference_number']);
			$dataTDC['id'] = $arrRefNo[1];
			$dataTDC['amount'] = $paymentData['Payment']['amount'];

			// Request complete payment Credit Card Processor
			//$REQ_SUCCESS = new instapago();

			// Check that Platform is valid
			//$resultPayment = $REQ_SUCCESS->completePayment($dataTDC);

			//$arrResultPayment = explode(':', $resultPayment);
			//$status = $arrResultPayment[0];
			//$referenceId = $arrResultPayment[2];

			// Getting Tax rate
			$taxArr = $this->Country->findById(
				$userData['User']['country_id']
			);
			$tax = $taxArr['Country']['tax'];

			// Getting Credit Card fees
			$feeArr = $this->Setting->find(
				'first'
			);
			$ccFee = $feeArr['Setting']['credit_card_fee_percent'];

			// Getting gross amount from Payments table
			$amount = $paymentData['Payment']['amount'];

			// Calculating Net Amount
			$netAmount = round((100 * $amount) / ($tax + 100), 2);

			// Calculating Credit Card fees
			if ($paymentData['Payment']['payment_method'] == 2) {
				$fees = round((($netAmount * $ccFee) / 100), 2);
			} else {
				$fees = 0;
			}

			// Calculating taxes paid
			$taxPaid = $amount - $netAmount;

			// Set taxes paid for Payments table
			$data['Payment']['tax'] = $taxPaid;

			// Check payment method and set Net Amount for Payments Table
			if ($paymentData['Payment']['payment_method'] == 2) {
				$netAmountWithFees = round($netAmount - $fees, 2);
				$data['Payment']['net_amount'] = $netAmountWithFees;
				$data['Payment']['fees'] = $fees;
				$netAmount = $netAmountWithFees;
			} else {
				$data['Payment']['net_amount'] = $netAmount;
			}

			// If it's a Reseller, calculate discounted amount
			if ($userData['User']['user_type'] == 2) {
				$newAmount = round((100 * $netAmount) / (100 - $userData['User']['discount_rate']), 2);
			} else {
				$newAmount = $netAmount;
			}

			// Set discount applied for the Payments table
			$discountApplied = $newAmount - $netAmount;
			$data['Payment']['discount'] = $discountApplied;

			// Set Balance added for the Payments table
			$data['Payment']['amount_credited'] = $newAmount;

			// Set balance for the user or reseller account
			$newBalance = $userData['User']['balance'] + $newAmount;

			// Set Payment information for User Account view
			$user_data['User']['id'] = $userID;
			$history['AccountHistory']['user_id'] = $userID;
			$history['AccountHistory']['payment_id'] = $data['Payment']['id'];
			$history['AccountHistory']['account_type'] = 1;
			$history['AccountHistory']['amount'] = $newAmount;
			$history['AccountHistory']['detail'] = __("Payment Notification Accepted");

			// Set invoice information for invoices table
			$invoice['Invoice']['payment_id'] = base64_decode($id);
			$invoice['Invoice']['nombre'] = $userData['User']['name'];
			$invoice['Invoice']['ruc'] = $userData['User']['tax_id'];
			$invoice['Invoice']['direccion'] = 'Venezuela'; // Hardcoded because fiscal printer has very little space for address

			// Calculating discount amount for fiscal printer
			$unRoundedNewAmount = (100 * (100 * $amount) / ($tax + 100)) / (100 - $userData['User']['discount_rate']);
			$retailAmount = $unRoundedNewAmount + ($unRoundedNewAmount * $tax / 100);
			$invoice['Invoice']['descuento'] = $retailAmount - $amount;

			$invoice['Invoice']['total_pagos'] = $amount;
			$invoice['Invoice']['total_final'] = $amount;

			// Generate invoice number
			$documentoId = base64_decode($id);
			$documento = 'FACTI' . str_pad($documentoId, 7, "0", STR_PAD_LEFT);
			$invoice['Invoice']['documento'] = $documento;

			// Check payment type and store in appropriate column
			if ($paymentData['Payment']['payment_method'] == 1) {
				$invoice['Invoice']['efectivo'] = $amount;
			} else if ($paymentData['Payment']['payment_method'] == 2) {
				$invoice['Invoice']['tarjeta_credito'] = $amount;
			}

			// Add credit card fees to the invoice
			if ($paymentData['Payment']['payment_method'] == 2) {
				$invoice['Invoice']['porcentaje_recargo'] = $ccFee;
				$invoice['Invoice']['recargos'] = $fees;
			}

			// Set appropriate product code and description
			if ($userData['User']['user_type'] == 1) {
				$invoice['Invoice']['codigo'] = 'CLUB-USR';
			} else if ($userData['User']['user_type'] == 2) {
				$invoice['Invoice']['codigo'] = 'CLUB-REV';
			}
			$invoice['Invoice']['nombre_articulo'] = 'Balance Club Prepago';
			$invoice['Invoice']['unidad'] = 'USD';
			$invoice['Invoice']['cantidad'] = $data['Payment']['amount_credited'];
			$invoice['Invoice']['precio_neto_unit'] = COST;
			$invoice['Invoice']['alicuota'] = $tax;

			// Save data to tables and generate confirmation email
			if ($this->Payment->save($data['Payment'])) {

				$this->User->save($user_data['User']);
				$this->AccountHistory->save($history['AccountHistory']);
				$this->User->id = $userData['User']['id'];
				$this->User->saveField("balance", $newBalance);

				// Save data to Invoice table and get invoice id
				$this->Invoice->save($invoice['Invoice']);
				$invoiceId = $this->Invoice->getInsertID();

				// Generate invoice
				$invoice = $this->admin_generate_invoice($invoiceId);

				// Get user's data
				$user_data = $this->User->find(
					'first',
					array(
						'conditions' => array('id' => $userID)
					)
				);

				// Set target email address
				$user_mail = $user_data['User']['email'];

				// Set email details
				$Email = new CakeEmail();
				$Email->template('payment_approved');
				$Email->emailFormat('html');
				$Email->config('smtp');
				$Email->to($user_data['User']['email']);
				$Email->subject('¡Tu pago ha sido aprobado!');

				// Set Email body variables
				$Email->viewVars(
					array(
						'username'           => $user_data['User']['name'],
						'payment_number'     => str_pad($paymentData['Payment']['id'], 7, "0", STR_PAD_LEFT),
						'notification_date'  => date('d-m-Y h:i:s a', strtotime($paymentData['Payment']['notification_date'])),
						'status_change'      => date('d-m-Y h:i:s a', strtotime($data['Payment']['change_status_date'])),
						'amount_net'         => number_format((float)$newAmount, 2, '.', ''),
						'amount_itbms'       => number_format((float)$taxPaid, 2, '.', ''),
						'amount_fees'        => number_format((float)$fees, 2, '.', ''),
						'amount_discount'    => number_format((float)$discountApplied, 2, '.', ''),
						'amount_total'       => number_format((float)$paymentData['Payment']['amount'], 2, '.', ''),
						'promo'              => $promo
					)
				);

				// Send email message
				$Email->send();

				// Generate success message
				$this->Session->write('success',"1");
				$this->Session->write('alert', __("Payment Request Accepted."));
				echo "<script>window.location.href='../payment_notifications'</script>";
				$this->render();

			// If something goes wrong, generate error message
			} else {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Your request could not be processed."));
				echo "<script>window.location.href='../payment_notifications'</script>";
				exit;
			}

		// If something goes wrong, generate error message
		} else {
			$this->Session->write('success', "0");
			$this->Session->write('alert', __("Your request could not be processed."));
			echo "<script>window.location.href='../payment_notifications'</script>";
			exit;
		}
	}

	/**
	 * Deny a payment action
	 */
	public function admin_deny($id) {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);
		$this->layout = '';

		// Create Payment model
		$this->loadModel('Payment');

		// Load User model
		$this->loadModel('User');

		// If a payment ID is specified
		if (!empty($id)) {
			$this->Payment->id = base64_decode($id);

			// Set status to denied
			$this->Payment->saveField("status", 2);

			// Set status change date
			$this->Payment->saveField("change_status_date", date('Y-m-d H:i:s', time()));

			// Set denial reason
			$this->Payment->saveField("denial_reason", $this->request->data['Payment']['denial_reason']);
			$paymentData = $this->Payment->find(
				'first',
				array(
					'conditions' => array('id' => base64_decode($id))
				)
			);
			$user_id = $paymentData['Payment']['user_id'];
			$reason = $paymentData['Payment']['denial_reason'];

			// Get user's data
			$user_data = $this->User->find(
				'first',
				array(
					'conditions' => array('id' => $user_id)
				)
			);

			// Set target email address
			$user_mail = $user_data['User']['email'];

			// Set email details
			$Email = new CakeEmail();
			$Email->template('payment_denied');
			$Email->emailFormat('html');
			$Email->config('smtp');
			$Email->to($user_data['User']['email']);
			$Email->subject('¡Tu notificación de pago ha sido rechazada!');

			// Set Email body variables
			$Email->viewVars(
				array(
					'username'           => $user_data['User']['name'],
					'payment_number'     => str_pad($paymentData['Payment']['id'], 7, "0", STR_PAD_LEFT),
					'notification_date'  => date('d-m-Y h:i:s a', strtotime($paymentData['Payment']['notification_date'])),
					'status_change'      => date('d-m-Y h:i:s a', strtotime($data['Payment']['change_status_date'])),
					'amount_total'       => number_format((float)$paymentData['Payment']['amount'], 2, '.', ''),
					'denial_reason'      => $reason
				)
			);

			// Send email message
			$Email->send();

			// Generate success message
			$this->Session->write('success', "1");
			$this->Session->write('alert', __("Payment Request rejected successfully."));
			echo "<script>window.location.href='../payment_history'</script>";
			exit;

		// If there was a problem, generate an error message
		} else {
			$this->Session->write('success', "0");
			$this->Session->write('alert', __("Payment Request could not be rejected."));
			echo "<script>window.location.href='../payment_history'</script>";
			exit;
		}
	}

	/**
	 * Deny payment confirmation
	 */
	public function admin_deny_confirmation($id) {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Load Payment model
		$this->loadModel('Payment');

		// Get payment details from id
		$paymentData = $this->Payment->find(
			'first',
			array(
				'conditions' => array('id' => base64_decode($id))
			)
		);
		$id = base64_encode($paymentData['Payment']['id']);
		$this->set('id', $id);
	}

	/**
	 * View payment rejection details
	 */
	public function admin_details($id) {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Load payment model
		$this->loadModel('Payment');

		// If a payment id is specified, seach the payments table for it
		if (!empty($id)) {
			$this->request->data = $this->Payment->find(
				'first',
				array(
					'conditions' => array('id' => base64_decode($id))
				)
			);
		}
		$this->set('details', $this->request->data);
	}

	/**
	 * Generate invoice file for fiscal printer TODO
	 */
	public function admin_generate_invoice($id) {
		$this->autoRender = false;

		// Initialize variablEs
		$contentTi = '';
		$contentMv = '';

		// Get data from invoices table
		$data = $this->Invoice->find(
			'first',
			array(
				'fields'     => array(
					'Invoice.*',
				),
				'conditions' => array(
					'Invoice.id' => $id
				)
			)
		);

		// If there is data to write to the invoice files
		if (!empty($data)) {

			// Fill rows with data
			foreach ($data as $invoice) {
				$contentTi .=
					"\"" . $invoice['documento'] . "\"" . "\t" .
					"\"" . $invoice['nombre'] . "\"" . "\t" .
					"\"" . $invoice['ruc'] . "\"" . "\t" .
					"\"" . $invoice['direccion'] . "\"" . "\t" .
					$invoice['descuento'] . "\t" .
					$invoice['total_pagos'] . "\t" .
					$invoice['total_final'] . "\t" .
					$invoice['recargos'] . "\t" .
					$invoice['porcentaje_recargo'] . "\t" .
					$invoice['efectivo'] . "\t" .
					$invoice['cheque'] . "\t" .
					$invoice['tarjeta_credito'] . "\t" .
					$invoice['nota_credito'] . "\t" .
					"\n";

			$contentMv .=
					$invoice['documento'] . "\t" .
					$invoice['codigo'] . "\t" .
					$invoice['nombre_articulo'] . "\t" .
					$invoice['unidad'] . "\t" .
					$invoice['cantidad'] . "\t" .
					$invoice['precio_neto_unit'] . "\t" .
					$invoice['alicuota'] . "\t" .
					"\n";
			}
		}

		// Generate invoice header file
		$path = realpath('../../app/invoices/') . '/';
		$fileNameTi = $invoice['documento'] . '.txt';
		$newFile = $path . $fileNameTi;
		file_put_contents($newFile, $contentTi);
		header('Content-type: text/plain');

		// Generate invoice movement file
		$path = realpath('../../app/invoices/') . '/';
		$invoiceId = str_pad($invoice['payment_id'], 7, "0", STR_PAD_LEFT);
		$fileNameMv = 'FACMV' . $invoiceId . '.txt';
		$newFile = $path . $fileNameMv;
		file_put_contents($newFile, $contentMv);
		header('Content-type: text/plain');

		return;
	}

	/**
	 * Pull credit card tranactions from credit card transactions table
	 */
	public function admin_GetTransDetail($id) {
		$this->autoRender = false;

		// Load Transaction model
		$this->loadModel('Transaction');

		// Find credit card transaction data
		if (!empty($id)) {
			return $this->Transaction->find(
				'first',
				array(
					'conditions' => array('id' => ($id))
				)
			);
		}
	}
}

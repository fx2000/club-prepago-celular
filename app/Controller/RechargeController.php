<?php
/**
 * Recharge Controller
 *
 * This file handles new recharges, recharge status checks
 * and manually changing failed recharge status.
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.Controller
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
class RechargeController extends AppController {

	// Adding Google Maps Helper
	public $helpers = array('GoogleMap');

	var $uses = array(
		'Admin',
		'Recharge',
		'Operator',
		'OperatorCredential',
		'Setting'
	);

	/**
	 * Get status of a recharge
	 */
	function admin_status() {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'));

		// Load standard layout
		$this->layout = 'admin_layout';
	}

	/**
	 * Get status of a recharge in TrxEngine with a link
	 */
	function admin_view_status($id) {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Find recharge details
		if (is_numeric(base64_decode($id))) {
			$this->request->data = $this->Recharge->find(
				'first',
				array(
					'fields'     => array(
						'User.name',
						'Operator.name',
						'Operator.productId',
						'OperatorCredential.*',
						'Recharge.*'
					),
					'conditions' => array(
						'merchant_txn_id' => base64_decode($id)
					),
					'joins'      => array(
						array(
							'table'      => 'operators',
							'alias'      => 'Operator',
							'type'       => 'INNER',
							'conditions' => array('Recharge.operator=Operator.id')
						),
						array(
							'table'      => 'users',
							'alias'      => 'User',
							'type'       => 'INNER',
							'conditions' => array('Recharge.user_id=User.id')
						),
						array(
							'table'      => 'operator_credentials',
							'alias'      => 'OperatorCredential',
							'type'       => 'INNER',
							'conditions' => array('Recharge.operator=OperatorCredential.operator_id')
						),
					)
				)
			);
			$rechageStatus = $this->request->data;
			$this->set('rechageStatus', $rechageStatus);

			// Prepare XML file for TrxEngine
			$trxid = $this->request->data['Recharge']['merchant_txn_id'];
			$operator = $this->request->data['Recharge']['operator'];
			$url = $this->request->data['OperatorCredential']['ip_address'];
			$port = $this->request->data['OperatorCredential']['port'];
			$merchantId = $this->request->data['OperatorCredential']['username'];
			$merchantPin = $this->request->data['OperatorCredential']['password'];
			$productId = $this->request->data['OperatorCredential']['product_id'];

			// Generate TrxEngine XML file
			$header[] = "Host:" . $url . ":" . $port;
			$header[] = "Content-type: text/xml";
			$NewXml =
					'<methodCall>
					<methodName>roms.eposrtxnpr</methodName>
					<params>
					<param><value><string>' . $merchantId . '</string></value></param>
					<param><value><string>' . $merchantPin . '</string></value></param>
					<param><value><string>' . $trxid . '</string></value></param>
					<param><value><string>' . $productId . '</string></value></param>
					</params>
					</methodCall>';

			// Start curl
			$ch = curl_init();

			// Set target server
			curl_setopt($ch, CURLOPT_URL, $url . ":" . $port);

			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

			// Add headers
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );

			// Set POST
			curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );

			// Add XML content
			curl_setopt($ch, CURLOPT_POSTFIELDS, $NewXml);

			// Send transaction to trxengine
			$result = curl_exec($ch);

			// Read trxengine response and return results
			$xmlArray = Xml::toArray(Xml::build($result));
			$arrRechargeStatus = explode(
				':', $xmlArray['methodResponse']['params']['param']['value']['string']
			);

			// Parse results
			$this->set('trxStatus', $arrRechargeStatus[1]);
			$this->set('trxResponse', $result);

		// If no transaction id is specified
		} else {
			$this->Session->write('success', "0");
			$this->Session->write('alert', __("Please enter a Transaction ID"));
			$this->redirect(
				array(
					'controller' => 'recharge',
					'action'     => 'status'
				)
			);
		}
	}

	/**
	 * Check status of a recharge in TrxEngine through a form
	 */
	function admin_check_status() {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// If the current user has permission
		if ($this->request->data) {
			$Admindata = $this->Admin->find(
				'first',
				array(
					'conditions' => array('id' => $this->Session->read('admin_id'))
				)
			);
			$this->set('Admindata', $Admindata);

			// Get local recharge information from recharges table
			$rechageStatus = $this->Recharge->find(
				'first',
				array(
					'fields'     => array(
						'User.name',
						'Operator.name',
						'Operator.productId',
						'OperatorCredential.*',
						'Recharge.*'
					),
					'conditions' => array(
						'merchant_txn_id' => $this->request->data['Recharge']['merchant_txn_id'],
					),
					'joins'      => array(
						array(
							'table'      => 'operators',
							'alias'      => 'Operator',
							'type'       => 'INNER',
							'conditions' => array('Recharge.operator=Operator.id')
						),
						array(
							'table'      => 'users',
							'alias'      => 'User',
							'type'       => 'INNER',
							'conditions' => array('Recharge.user_id=User.id')
						),
						array(
							'table'      => 'operator_credentials',
							'alias'      => 'OperatorCredential',
							'type'       => 'INNER',
							'conditions' => array('Recharge.operator=OperatorCredential.operator_id')
						),
					)
				)
			);

			// If recharge is found, return recharge status
			if (!empty($rechageStatus)) {
				$this->set('rechageStatus', $rechageStatus);

				// Prepare XML file for TrxEngine
				$trxid = $this->request->data['Recharge']['merchant_txn_id'];
				$operator = $rechageStatus['OperatorCredential']['operator_id'];
				$url = $rechageStatus['OperatorCredential']['ip_address'];
				$port = $rechageStatus['OperatorCredential']['port'];
				$merchantId = $rechageStatus['OperatorCredential']['username'];
				$merchantPin = $rechageStatus['OperatorCredential']['password'];
				$productId = $rechageStatus['OperatorCredential']['product_id'];

				// Generate TrxEngine XML file
				$header[] = "Host:" . $url . ":" . $port;
				$header[] = "Content-type: text/xml";
				$NewXml =
						'<methodCall>
						<methodName>roms.eposrtxnpr</methodName>
						<params>
						<param><value><string>' . $merchantId . '</string></value></param>
						<param><value><string>' . $merchantPin . '</string></value></param>
						<param><value><string>' . $trxid . '</string></value></param>
						<param><value><string>' . $productId . '</string></value></param>
						</params>
						</methodCall>';

				// Start curl
				$ch = curl_init();

				// Set target server
				curl_setopt($ch, CURLOPT_URL, $url . ":" . $port);
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

				// Add headers
				curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );

				// Set POST
				curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );

				// Add XML content
				curl_setopt($ch, CURLOPT_POSTFIELDS, $NewXml);

				// Send transaction to trxengine
				$result = curl_exec($ch);

				// Read trxengine response and return results
				$xmlArray = Xml::toArray(Xml::build($result));
				$arrRechargeStatus = explode(
					':', $xmlArray['methodResponse']['params']['param']['value']['string']
				);

				// Parse results
				$this->set('trxStatus', $arrRechargeStatus[0]);
				$this->set('trxResponse', $result);

			// Otherwise, return an error message
			} else {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("No match Found"));
				$this->redirect(
					array(
						'controller' => 'recharge',
						'action'     => 'status'
					)
				);
			}


		// If no transaction ID is entered
		} else {
			$this->Session->write('success', "0");
			$this->Session->write('alert', __("Please enter a Transaction ID"));
			$this->redirect(
				array(
					'controller' => 'recharge',
					'action'     => 'status'
				)
			);
		}
	}

	/**
	 * Retry recharge
	 */
	public function admin_retry() {
		$this->autoRender = false;

		// Get original recharge id
		$rechargeId = base64_decode($this->params['pass'][0]);

		// Get original recharge data using recharge id
		$rechagedata = $this->Recharge->find(
			'first',
			array(
				'conditions' => array('id' => $rechargeId)
			)
		);

		// Get operator's product id
		$operatordata = $this->OperatorCredential->find(
			'first',
			array(
				'conditions' => array('OperatorCredential.operator_id' => $rechagedata['Recharge']['operator'])
			)
		);

		// Calculate points the user will earn if the recharge is successful
		$settings = $this->Setting->query(
			"SELECT *
				FROM settings"
		);

		// Make sure a user only gets poinmts for whole dollar amounts
		$points = $settings[0]['settings']['reward_recharge'] * floor($rechagedata['Recharge']['amount']);

		// Get current date & time
		$date = date('Y-m-d H:i:s');

		// Insert preliminary recharge information into recharges table
		$data['Recharge']['user_id'] = $rechagedata['Recharge']['user_id'];
		$data['Recharge']['user_type'] = $rechagedata['Recharge']['user_type'];
		$data['Recharge']['phone_number'] = $rechagedata['Recharge']['phone_number'];
		$data['Recharge']['operator'] = $rechagedata['Recharge']['operator'];
		$data['Recharge']['amount'] = $rechagedata['Recharge']['amount'];
		$data['Recharge']['tax_amount'] = $rechagedata['Recharge']['tax_amount'];
		$data['Recharge']['total_amount'] = $rechagedata['Recharge']['total_amount'];
		$data['Recharge']['payment_method'] = $rechagedata['Recharge']['payment_method'];
		$data['Recharge']['promo_number'] = $rechagedata['Recharge']['promo_number'];
		$data['Recharge']['payment_id'] = $rechagedata['Recharge']['payment_id'];
		$data['Recharge']['recharge_date'] = $date;
		$data['Recharge']['x'] = $rechagedata['Recharge']['x'];
		$data['Recharge']['y'] = $rechagedata['Recharge']['y'];
		$this->Recharge->save($data['Recharge']);

		// Generate merchant_txn_id for TrxEngine
		$preRechargeId = $this->Recharge->getInsertID();
		$merchantTxnId = str_pad($preRechargeId, 10, "0", STR_PAD_LEFT);

		// Prepare XML file for TrxEngine
		$url = $operatordata['OperatorCredential']['ip_address'];
		$port = $operatordata['OperatorCredential']['port'];
		$merchantId = $operatordata['OperatorCredential']['username'];
		$merchantPin = $operatordata['OperatorCredential']['password'];
		$productId = $operatordata['OperatorCredential']['product_id'];

		// Generate TrxEngine XML file
		$header[] = "Host:" . $url . ":" . $port;
		$header[] = "Content-type: text/xml";
		$NewXml =
			'<methodCall>
			<methodName>roms.esinglextrapr</methodName>
			<params>
			<param><value>' . $merchantId .'</value></param>
			<param><value>' . $merchantPin .'</value></param>
			<param><value>' . $rechagedata['Recharge']['phone_number'] .'</value></param>
			<param><value>' . $rechagedata['Recharge']['amount'] .'</value></param>
			<param><value>' . $merchantTxnId .'</value></param>
			<param><value>' . $rechagedata['Recharge']['user_type'] . '</value></param>
			<param><value>' . $rechagedata['Recharge']['user_id'] . '</value></param>
			<param><value>' . date('YmdHis') . '</value></param>
			<param><value>' . $productId . '</value></param>
			</params>
			</methodCall>';
		$ch = curl_init();

		// Check Mobile Operator and set target server
		curl_setopt($ch, CURLOPT_URL, $url . ":" . $port);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );

		// Add headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		// Set POST
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

		// Add XML content
		curl_setopt($ch, CURLOPT_POSTFIELDS, $NewXml);

		// Send transaction to trxengine
		$result = curl_exec($ch);

		// Close connection
		curl_close($ch);

		// Read trxengine response and return results
		$xmlArray = Xml::toArray(Xml::build($result));
		$ArrRechargeStatus = explode(
			':', $xmlArray['methodResponse']['params']['param']['value']['string']
		);
		$status = $ArrRechargeStatus[1];

		// If Recharge is successful
		if ($status == '00') {
			$RechargeDone = 1;
			$message =
				__('Your recharge has been successful, the original recharge has been marked as fixed and a new recharge has been added to the database as replacement');
			$amount = $rechagedata['Recharge']['total_amount'];

			// Insert points earned into transaction
			$updRecharge =  $this->Recharge->query(
				"UPDATE recharges
					SET points = " . $points .
					" WHERE id = " . $preRechargeId
			);

			// Adjust the user's points
			$updUserPoints = $this->User->query(
				"UPDATE users
					SET points = points + " . $points . " WHERE id = ". $rechagedata['Recharge']['user_id']
			);

			// Subtract recharge amount from mobile operator balance
			$UpdOperatorBal = $this->Recharge->query(
				"UPDATE operators SET balance = balance - " . $rechagedata['Recharge']['amount'] . " WHERE id = " .
					$rechagedata['Recharge']['operator']
			);
		} else {

			// If Recharge failed, generate error message
			$RechargeDone = 0;

			switch ($status) {
				case 1:
					$messageCode = '564';
					$message = 'Error: Improper MerchantID';
					break;
				case 2:
					$messageCode = '565';
					$message = 'Error: Improper CustomerPhoneNo';
					break;
				case 3:
					$messageCode = '566';
					$message = 'Error: Improper MerchantPIN';
					break;
				case 4:
					$messageCode = '567';
					$message = 'Error: The minimum amount is ' . $ArrRechargeStatus[2];
					break;
				case 5:
					$messageCode = '568';
					$message = 'Error: The maximum amount is ' . $ArrRechargeStatus[2];
					break;
				case 6:
					$messageCode = '569';
					$message = 'Error: Operation not supported or data inconsistency';
					break;
				case 7:
					$messageCode = '570';
					$message = 'Error: Remote system unavailable';
					break;
				case 8:
					$messageCode = '571';
					$message = 'Error: Insufficient funds';
					break;
				case 9:
					$messageCode = '572';
					$message = 'Error: Duplicate Transaction.';
					break;
				case 10:
					$messageCode = '573';
					$message = 'Error: Missing MerchantID, CustomerPhoneNo, MerchantPIN, TopupAmount';
					break;
				case 11:
					$messageCode = '574';
					$message = 'Error: Improper ProductID';
					break;
				case 12:
					$messageCode = '575';
					$message = 'Error: Merchant account has been disabled';
					break;
				case 13:
					$messageCode = '576';
					$message = 'Error: Improper Terminal';
					break;
				default:
					$messageCode = '577';
					$message = 'Error: Something went wrong';
					break;
			}
		}

		// Insert final Recharge information into recharges table
		$updRecharge =  $this->Recharge->query(
			"UPDATE recharges
				SET status = " .
					$RechargeDone . "," .
					" merchant_txn_id = " . "\"" . $merchantTxnId . "\"" . "," .
					" response_code = " . "\"" . $status . "\"" . "," . 
					" response_message = " . "\"" . $message . "\"" .
				" WHERE id = " . $preRechargeId
		);

		// Update failed recharge with new recharge information and mark as fixed
		$updOriginalRecharge =  $this->Recharge->query(
			"UPDATE recharges
				SET status = " .
					2 . "," .
					" replaced_by = " . "\"" . $merchantTxnId . "\"" .
				" WHERE id = " . $rechagedata['Recharge']['id']
		);

		// If the recharge was successful, return 1
		if ($RechargeDone == 1) {
			$this->Session->write('success', "1");

		// Otherwise return 0
		} else {
			$this->Session->write('success', "0");
		}

		// Send the final status message
		$this->Session->write('alert', $message);

		// And redirect the user back to the failed recharges list
		$this->redirect(
			array(
				'controller' => 'recharge',
				'action'     => 'failed'
			)
		);
	}

	/**
	 * List failed recharges for manual status change
	 */
	public function admin_failed() {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Find data in recharges table
		$userdata = $this->Recharge->find(
			'all',
			array(
				'conditions' => array(
					'Recharge.status'         => 0,
					'Recharge.payment_method' => 2
				),
				'fields'     => array(
					'User.name',
					'User.user_type',
					'User.id',
					'User.delete_status',
					'Operator.name',
					'Recharge.*'
				),
				'joins'      => array(
					array(
						'table'      => 'operators',
						'alias'      => 'Operator',
						'type'       => 'INNER',
						'conditions' => array('Recharge.operator=Operator.id')
					),
					array(
						'table'      => 'users',
						'alias'      => 'User',
						'type'       => 'INNER',
						'conditions' => array('Recharge.user_id=User.id')
					)
				)
			)
		);
		$this->set('userdata', $userdata);

	}
}

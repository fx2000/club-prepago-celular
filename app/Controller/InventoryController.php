<?php
/**
 * Inventory Controller
 *
 * This file manages mobile operator inventory, airtime purchase history,
 * airtime history exports and setting minimum account limits.
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.Controller
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
class InventoryController extends AppController{
	var $uses = array(
		'Setting',
		'Operator',
		'Admin',
		'AirtimePurchaseHistory',
		'User',
		'Reseller'
	);
	var $components = array('Validation');

	/**
	 * List inventories
	 */
	public function admin_index()  {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Add airtime purchase
		if (!empty($this->request->data)) {

			// Update operators table
			$qry =
				"UPDATE operators
					SET balance = balance + " . $this->request->data['Inventory']['amount'] .
					" WHERE id = " . $this->request->data['Inventory']['operator'];
			$data = $this->Operator->query($qry);

			// Update air time purchase history table
			$history['AirtimePurchaseHistory']['amount'] = $this->request->data['Inventory']['amount'];
			$history['AirtimePurchaseHistory']['operator'] = $this->request->data['Inventory']['operator'];
			$history['AirtimePurchaseHistory']['document_number'] = $this->request->data['Inventory']['document_number'];
			$this->AirtimePurchaseHistory->save($history);

			// Display success message
			$this->Session->write('success', "1");
			$this->Session->write('alert', __("Amount added to inventory"));

			// Go back to inventory index
			$this->redirect(
				array(
					'controller' => 'inventory',
					'action'     => 'index'
				)
			);
		}
		$data = $this->Operator->find('all');
		$this->set('userdata', $data);

		// List mobile operators
		$Operatordata = $this->Operator->find(
			'list',
			array(
				'fields' => array('name')
			)
		);
		$this->set('Operatordata', $Operatordata);

		// Current staff member id
		$Admindata = $this->Admin->find(
			'first',
			array(
				'conditions' => array('id' => $this->Session->read('admin_id')
				)
			)
		);
		$this->set('Admindata', $Admindata);

		// List prior air time purchases
		$AccHistory = $this->AirtimePurchaseHistory->find(
			'all',
			array(
				'fields' => array(
					'AirtimePurchaseHistory.*',
					'Operator.name'
				),
				'order'  => 'purchase_date desc',
				'joins'  => array(
					array(
						'table'      => 'operators',
						'alias'      => 'Operator',
						'type'       => 'INNER',
						'conditions' => array('AirtimePurchaseHistory.operator=Operator.id')
					)
				)
			)
		);
		$this->set('AccHistory', $AccHistory);
	}

	/**
	 * Airtime purchase history exports
	 */
	public function admin_export() {

		// Find data to fill file
		$this->autoRender = false;
		$data = $this->AirtimePurchaseHistory->find(
			'all',
			array(
				'fields' => array(
					'AirtimePurchaseHistory.*',
					'Operator.name'
				),
				'order'  => 'purchase_date desc',
				'joins'  => array(
					array(
						'table'      => 'operators',
						'alias'      => 'Operator',
						'type'       => 'INNER',
						'conditions' => array('AirtimePurchaseHistory.operator=Operator.id')
					)
				)
			)
		);
		$content = '';

		// Generate column headers
		if (!empty($data)) {
			$content .=  "Mobile Operator,Amount,Document Number,Date & Time" . "\n";

			// Fill rows with data
			foreach ($data As $recharge) {
				$content .=
					$recharge['Operator']['name'] . "," .
					$recharge['AirtimePurchaseHistory']['amount'] . "," .
					$recharge['AirtimePurchaseHistory']['document_number'] . "," .
					date('Y-m-d H:i:s',strtotime($recharge['AirtimePurchaseHistory']['purchase_date'])) . "\n";
			}
		}

		// Generate new file
		$path = realpath('../../app/webroot/uploads/') . '/';
		$FileName = 'MobileOperatorPurchases.csv';
		$NewFile = $path.$FileName;
		file_put_contents($NewFile, $content);
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="' . $FileName . '"');
		readfile($NewFile);
		exit();
	}

	/**
	 * Manage inventory warning levels
	 */
	public function admin_warning() {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';


		// List mobile operators and their warning levels
		if (!empty($this->request->data)) {
			$operators = $this->Operator->find('all');

			foreach ($operators as $operator) {
				$var  ='min_limit' . $operator['Operator']['id'];
				$value['Operator']['minimum_limit'] = $this->request->data['Operator'][$var];
				$value['Operator']['id'] = $operator['Operator']['id'];
				$this->Operator->save($value);
				$this->Session->write('success', "1");
				$this->Session->write('alert', __("Warning level set"));
			}
			$this->redirect(
				array(
					'controller' => 'inventory',
					'action'     => 'warning'
				)
			);
		} else {
			$this->request->data = $this->Operator->find('all');
		}
	}
}

<?php
/**
 * Banks Controller
 *
 * Adding, removing and editing banks is controlled with this file
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.Controller
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
class BanksController extends AppController {
	var $uses = array(
		'User',
		'Recharge',
		'Redemption',
		'Admin',
		'UserAccountHistory',
		'Payment',
		'Bank'
	);
	var $components = array('Validation');

	/**
	 * View all banks
	 */
	public function admin_index() {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Create Bank model
		$this->loadModel('Bank');

		// Search banks table for all active banks
		$data = $this->Bank->find(
			'all',
			array(
				'conditions' => array('delete_status' => '0'),
				'order'      => 'id desc'
			)
		);
		$this->set('bankdata', $data);
		$Admindata = $this->Admin->find(
			'first',
			array(
				'conditions' => array(
					'id' => $this->Session->read('admin_id')
				)
			)
		);
		$this->set('Admindata', $Admindata);
	}

	/**
	 * Add bank
	 */
	public function admin_add() {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Validate the new bank's name
		if (!empty($this->request->data)) {
			$exist_Bank = $this->Bank->find(
				'all',
				array(
					'conditions' => array(
						'bank_name'      => $this->request->data['Bank']['bank_name'],
						'delete_status'  => 0,
						'account_number' => $this->request->data['Bank']['account_number'],
						'account_type' => $this->request->data['Bank']['account_type'],
					)
				)
			);
			
			// If no bank name is specified
			if ($this->Validation->Presence($this->request->data['Bank']['bank_name'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Bank name cannot be blank"));
				$this->render();
			}

			// If no account number is specified
			if ($this->Validation->Presence($this->request->data['Bank']['account_number'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Account number cannot be blank"));
				$this->render();
			}

			// If no account type is specified
			if ($this->Validation->Presence($this->request->data['Bank']['account_type'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Account type cannot be blank"));
				$this->render();

			// If the bank already exists
			} else if (!empty($exist_Bank)) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', "Bank already exists.");
				$this->render();

			// If all goeds well, proceed
			} else {
				$data['Bank'] = $this->request->data['Bank'];
				$data['Bank']['id'] = '';
				$data['Bank']['delete_status'] = 0;

				// Send a success message
				if($this->Bank->save($data['Bank'])) {
					$this->Session->write('success', "1");
					$this->Session->write('alert', __("Bank added successfully"));

					// And go back to view banks
					$this->redirect(
						array(
							'controller' => 'banks',
							'action'     => 'index'
						)
					);

				// Otherwise send an error message
				} else {
					$this->Session->write('success', "0");
					$this->Session->write('alert', __("Bank could not be created"));
					$this->render();
				}
			}
		}
	}

	/**
	 * Edit bank
	 */
	public function admin_edit($id) {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Validate the bank's new name name and id
		if (!empty($this->request->data)) {
			$exist_Bank = $this->Bank->find(
				'all',
				array(
					'conditions' => array(
						'bank_name' => $this->request->data['Bank']['bank_name'],
						'id !='     => $this->request->data['Bank']['id']
					)
				)
			);

			// If no bank name is specified
			if ($this->Validation->Presence($this->request->data['Bank']['bank_name'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Bank Name cannot be blank"));
				$this->render();

			// If the bank already exists
			} else if (!empty($exist_Bank)) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Bank already exists"));
				$this->render();

			// If all goeds well, proceed
			} else {
				$data['Bank'] = $this->request->data['Bank'];

				// Send a success message
				if($this->Bank->save($data['Bank'])) {
					$this->Session->write('success', "1");
					$this->Session->write('alert', __("Bank updated successfully"));

				// Otherwise send an error message
				} else {
					$this->Session->write('success', "0");
					$this->Session->write('alert', __("Bank could not be updated"));
				}
			}

			// And go back to edit bank
			$this->redirect(
				array(
					'controller' => 'banks',
					'action'     => 'edit',
					base64_encode($this->request->data['Bank']['id'])
				)
			);
		} else {
			
			// If the banks's id is valid
			if (is_numeric(base64_decode($id))) {
				$this->request->data = $this->Bank->find(
					'first',
					array(
						'conditions' => array(
							'id' => base64_decode($id)
						)
					)
				);

			// If the banks's id is invalid
			} else {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Invalid Bank"));
			}
		}
	}

	/**
	 * Delete bank
	 */
	public function admin_delete() {
		$this->layout = '';

		// Get bank ID
		$bank_id = !empty($this->params->pass[0]) ? base64_decode($this->params->pass[0]) : '';
		
		// If valid, set delete status to 1
		if (!empty($bank_id)) {
			$data['Bank']['id'] = $bank_id;
			$data['Bank']['delete_status'] = '1';
			
			// And trigger a success message
			if($this->Bank->save($data['Bank'])) {
				$this->Session->write('success', "1");
				$this->Session->write('alert', __("Bank Deleted"));

			// Otherwise, send an error message
			} else {
				$this->Session->write('success',"0");
				$this->Session->write('alert',__("Unable to delete Bank"));
			}

		// If bank ID is invalid, send an error message
		} else {
			$this->Session->write('success', "0");
			$this->Session->write('alert', __("Invalid Bank ID provided"));
		}

		// And go back to view banks
		$this->redirect(
			array(
				'controller' => 'banks',
				'action'     => 'index'
			)
		);
	}

	/**
	 * Get a bank's name from it's id
	*/
	public function admin_getBankName($id) {
		$this->autoRender = false;
		
		// If a Bank ID is specified, find it in the table
		if (!empty($id)) {
			$bank = $this->Bank->find(
				'first',
				array(
					'conditions' => array(
						'id' => $id
					)
				)
			);

			// And return the name
			return $bank;

		// Otherwise return blank
		} else {
			return '';
		}
	}

	/**
	 * Get bank by id
	*/
	public function admin_getBankByID($id) {
		$this->layout = '';
		
		// If a bank id is specified, search banks table
		if (!empty($id)) {
			$bank = $this->Bank->find(
				'first',
				array(
					'conditions' => array(
						'id' => $id
					)
				)
			);
			return $bank;
		}
	}

}

<?php
/**
 * Stores Controller
 *
 * Adding, removing and editing banks is controlled with this file
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.Controller.Stores
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
class StoreController extends AppController {
	var $uses = array(
		'User',
		'Recharge',
		'Redemption',
		'Admin',
		'UserAccountHistory',
		'Payment',
		'Bank',
    'Coupon',
    'Store'
	);
	var $components = array('Validation');

	/**
	 * View all stores
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

		// Create Store model
		$this->loadModel('Store');

		// Search stores table for all active stores
		$data = $this->Store->find(
			'all',
			array(
				'conditions' => array('delete_status' => '0'),
				'order'      => 'id desc'
			)
		);
		$this->set('storedata', $data);
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
	 * Add store
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

		// Validate the new store's name
		if (!empty($this->request->data)) {
			$exist_Store = $this->Store->find(
				'all',
				array(
					'conditions' => array(
						'name'      => $this->request->data['Store']['name'],
						'delete_status'  => 0,
						'address' => $this->request->data['Store']['address'],
						'email' => $this->request->data['Store']['email'],
					)
				)
			);

			// If no store name is specified
			if ($this->Validation->Presence($this->request->data['Store']['name'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Store name cannot be blank"));
				$this->render();
			}

			// If no address is specified
			if ($this->Validation->Presence($this->request->data['Store']['address'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Address cannot be blank"));
				$this->render();
			}

			// If no email type is specified
			if ($this->Validation->Presence($this->request->data['Store']['email'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Email cannot be blank"));
				$this->render();

			// If the store already exists
    } else if (!empty($exist_Store)) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', "Store already exists.");
				$this->render();

			// If all goeds well, proceed
			} else {
				$data['Store'] = $this->request->data['Store'];
				$data['Store']['id'] = '';
				$data['Store']['delete_status'] = 0;

				// Send a success message
				if($this->Store->save($data['Store'])) {
					$this->Session->write('success', "1");
					$this->Session->write('alert', __("Store added successfully"));

					// And go back to view stores
					$this->redirect(
						array(
							'controller' => 'store',
							'action'     => 'index'
						)
					);

				// Otherwise send an error message
				} else {
					$this->Session->write('success', "0");
					$this->Session->write('alert', __("Store could not be created"));
					$this->render();
				}
			}
		}
	}

  /**
	 * Edit store
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

		// Validate the store's new name and id
		if (!empty($this->request->data)) {
			$exist_Store = $this->Store->find(
				'all',
				array(
					'conditions' => array(
						'name' => $this->request->data['Store']['name'],
						'id !='     => $this->request->data['Store']['id']
					)
				)
			);

			// If no store name is specified
			if ($this->Validation->Presence($this->request->data['Store']['name'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Store Name cannot be blank"));
				$this->render();

			// If the store already exists
    } else if (!empty($exist_Store)) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Store already exists"));
				$this->render();

			// If all goeds well, proceed
			} else {
				$data['Store'] = $this->request->data['Store'];

				// Send a success message
				if($this->Store->save($data['Store'])) {
					$this->Session->write('success', "1");
					$this->Session->write('alert', __("Store updated successfully"));

				// Otherwise send an error message
				} else {
					$this->Session->write('success', "0");
					$this->Session->write('alert', __("Store could not be updated"));
				}
			}

			// And go back to edit store
			$this->redirect(
				array(
					'controller' => 'store',
					'action'     => 'edit',
					base64_encode($this->request->data['Store']['id'])
				)
			);
		} else {

			// If the store's id is valid
			if (is_numeric(base64_decode($id))) {
				$this->request->data = $this->Store->find(
					'first',
					array(
						'conditions' => array(
							'id' => base64_decode($id)
						)
					)
				);

			// If the stores's id is invalid
			} else {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Invalid Store"));
			}
		}
	}

	/**
	 * Delete store
	 */
	public function admin_delete() {
		$this->layout = '';

		// Get store ID
		$store_id = !empty($this->params->pass[0]) ? base64_decode($this->params->pass[0]) : '';

		// If valid, set delete status to 1
		if (!empty($store_id)) {
			$data['Store']['id'] = $store_id;
			$data['Store']['delete_status'] = '1';

			// And trigger a success message
			if($this->Store->save($data['Store'])) {
				$this->Session->write('success', "1");
				$this->Session->write('alert', __("Store Deleted"));

			// Otherwise, send an error message
			} else {
				$this->Session->write('success',"0");
				$this->Session->write('alert',__("Unable to delete Store"));
			}

		// If store ID is invalid, send an error message
		} else {
			$this->Session->write('success', "0");
			$this->Session->write('alert', __("Invalid Store ID provided"));
		}

		// And go back to view stores
		$this->redirect(
			array(
				'controller' => 'stores',
				'action'     => 'index'
			)
		);
	}

}
